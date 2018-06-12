<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\AdvancedImport\Readers;

use Tygh\Common\OperationResult;
use DOMDocument;
use XMLReader;

class Xml implements IReader
{
    const SCHEMA_PROBE_ROW_MAX_NUMBERS = 10;
    const TARGET_NODE_PATH_DELIMITER = '->';

    /** @var string $path Path to target file */
    protected $path;

    /** @var  XMLReader $reader Reader instance */
    protected $reader;

    /** @var array $current_path Array that contains current path of parsing file */
    protected $current_path = array();

    /** @var array $result Xml converted to an array */
    protected $result = array();

    /** @var array $options Array of options */
    protected $options = array();

    /** @inheritdoc */
    public function __construct($path, array $options = array())
    {
        $this->path = $path;
        $this->reader = new XMLReader();
        $this->options = $options;
    }

    /** @inheritdoc */
    public function getSchema()
    {
        $result = new OperationResult(false, array());
        $contents = $this->getContents(self::SCHEMA_PROBE_ROW_MAX_NUMBERS);

        if (!empty($contents)) {
            $schema = array_reduce($contents, function ($schema, $item) {
                if ($schema === null) {
                    $schema = array_keys($item);
                } else {
                    $schema = array_intersect($schema, array_keys($item));
                }

                return $schema;
            }, null);

            $schema = array_values(array_unique($schema));
            $result->setData($schema);
        }

        if (!empty($schema)) {
            $result->setSuccess(true);
        } else {
            $result->setWarnings(array(
                'check_target_node' => __('advanced_import.fetching_schema_failed_check_file')
            ));
        }

        return $result;
    }

    /** @inheritdoc */
    public function getContents($count = null, array $schema = null)
    {
        $contents = array();
        $counter = 0;
        $node = $this->getTargetNode((int) $count);

        if ($node) {

            if (isset($node['values'])) {
                $node = $node['values'];
            } else {
                $node = array($node);
            }

            // iterate trough parent nodes
            foreach ($node as $item) {
                if ($count === $counter) {
                    break;
                }

                $contents[$counter] = array_fill_keys(
                    array_values((array) $schema),
                    null
                );

                if (isset($item['attributes'])) {

                    foreach ($item['attributes'] as $attr_name => $attr_value) {

                        if ($schema && !in_array($attr_name, $schema)) {
                            continue;
                        }

                        $contents[$counter][$attr_name] = $attr_value;
                    }

                    unset($item['attributes']);
                }

                // iterate trough parent node's elements
                foreach ($item as $element_name => $elements) {

                    if (isset($elements['values'])) {
                        $elements = $elements['values'];
                    } elseif (count($elements) === 2
                        && isset($elements['attributes'])
                        && !isset($elements['value'])
                    ) {
                        /**
                         * Obtain values from items that are directly nested into the parent node.
                         * E.g.:
                         * <images>
                         *     <image>path/to/image_1.jpg</image>
                         *     <image>path/to/image_2.jpg</image>
                         *     <image>path/to/image_3.jpg</image>
                         * </images>
                         * =>
                         * [ path/to/image_1.jpg, path/to/image_2.jpg, path/to/image_3.jpg ]
                         */
                        $element_properties = fn_array_combine(array_keys($elements), array_keys($elements));
                        unset($element_properties['attributes']);
                        $singular_element_tag = reset($element_properties);
                        if (isset($elements[$singular_element_tag]['values'])) {
                            $elements = $elements[$singular_element_tag]['values'];
                        } elseif (isset($elements[$singular_element_tag]['value'])) {
                            $elements = array($elements[$singular_element_tag]);
                        } else {
                            $elements = array($elements);
                        }
                    } else {
                        $elements = array($elements);
                    }

                    foreach ($elements as $element) {
                        $value = isset($element['value']) ? $element['value'] : '';
                        $item_name = $element_name;

                        if (!empty($element['attributes'])) {
                            $item_name = $this->getItemNameWithAttributes($element_name, (array) $element['attributes']);
                        }

                        $current_value = isset($contents[$counter][$item_name]) ? $contents[$counter][$item_name] : '';

                        if ($current_value && $value) {
                            $value =  "{$current_value},{$value}";
                        }

                        if ($schema && !in_array($item_name, $schema)) {
                            continue;
                        }

                        $contents[$counter][$item_name] = $value;
                    }
                }

                $counter++;
            }
        }

        return $contents;
    }

    /** @inheritdoc */
    public function getApproximateLinesCount()
    {
        $target_node_path = $this->getTargetPath();
        $nodes_to_count = end($target_node_path);

        if (!empty($nodes_to_count)) {
            $dom = new DOMDocument();
            $dom->load($this->path);

            $nodes_list = $dom->getElementsByTagName($nodes_to_count);
            return $nodes_list->length;
        }

        return 0;
    }

    /**
     * Parses xml into array
     *
     * @param int $count Quantity of target nodes to parse
     *
     * @return array
     */
    public function parse($count = -1)
    {
        $target_node_path = $this->getTargetPath();
        $target_node = end($target_node_path);
        $parsing_target = false;

        $opened_nodes = array();

        $this->result = array();
        $this->current_path = array();
        $this->reader->open($this->path);

        while ($this->reader->read()) {
            $node_name = $this->reader->name;
            $node_type = $this->reader->nodeType;

            if ($node_type === XMLReader::END_ELEMENT) {

                if (end($opened_nodes) === $node_name) {
                    array_pop($opened_nodes);

                    if ($node_name === $target_node) {
                        $parsing_target = false;

                        $count--;

                        if ($count === 0) {
                            break;
                        }
                    }
                }

                continue;

            } if ($node_type === XMLReader::ELEMENT) {

                if (!$parsing_target && !in_array($node_name, $target_node_path)) {
                    continue;
                }

                if ($node_name === $target_node) {
                    $parsing_target = true;
                }

                $opened_nodes[] = $node_name;
                $path_key = $this->reader->depth;

                $this->current_path[$path_key] = $node_name;

                foreach ($this->current_path as $key => $path_item) {
                    if ($key > $path_key) {
                        unset($this->current_path[$key]);
                    }
                }

                $attributes = $this->getAttributes();
                $this->createParentItem($attributes);

            } elseif ($node_type === XMLReader::TEXT || $node_type === XMLReader::CDATA) {

                if (!$parsing_target) {
                    continue;
                }

                $value = '';

                if ($this->reader->hasValue) {
                    $value = $this->reader->value;
                }

                $this->storeValue($value);
            }
        }

        $this->reader->close();
        return $this->result;
    }

    /**
     * Generates name for element that contains attributes
     *
     * @param string $name       Name of parent node
     * @param array  $attributes Attributes as 'name' => 'value' pairs
     *
     * @return string
     */
    protected function getItemNameWithAttributes($name, array $attributes)
    {
        array_walk($attributes, function (&$value, $name) {
            $value = "{$name}: {$value}";
        });

        return implode('', array(
            $name,
            ' (',
            implode('; ', $attributes),
            ')'
        ));
    }

    /**
     * Fetches array of target elements from xml
     *
     * @param int $count Quantity of target nodes to be parsed
     *
     * @return array|bool|mixed
     */
    protected function getTargetNode($count = -1)
    {
        $node = false;
        $target_node_path = $this->getTargetPath();

        if (!empty($target_node_path)) {
            $node = $this->parse((int) $count);

            foreach ($target_node_path as $key) {
                if (isset($node[$key])) {
                    $node = &$node[$key];
                } else {
                    $node = false;
                    break;
                }
            }
        }

        return $node;
    }

    /**
     * Fetches node's attributes
     *
     * @return array
     */
    protected function getAttributes()
    {
        $attributes = array();

        if ($this->reader->hasAttributes) {
            while ($this->reader->moveToNextAttribute()) {
                $attributes[$this->reader->name] = $this->reader->value;
            }
        }

        return $attributes;
    }

    /**
     * Created node inside the result array
     *
     * @param array $attributes Node's attributes
     *
     * @return $this
     */
    protected function createParentItem($attributes = array())
    {
        $path_length = count($this->current_path);
        $current_element = &$this->result;

        foreach ($this->current_path as $key) {
            $path_length--;

            if (!array_key_exists($key, $current_element)) {
                $current_element[$key] = array();
            }

            if (isset($current_element[$key])) {
                $current_element = &$current_element[$key];
            }

            if ($path_length === 0) {

                if (!empty($current_element)) {

                    if (isset($current_element['values'])) {
                        $max_key = max(array_keys($current_element['values']));
                        $current_element['values'][$max_key + 1]['attributes'] = $attributes;

                    } else {
                        $attr = $current_element['attributes'];
                        unset($current_element['attributes']);
                        $rest = $current_element;

                        foreach ($current_element as $key => $value) {
                            unset($current_element[$key]);
                        }

                        $current_element['values'][0] = $rest;
                        $current_element['values'][0]['attributes'] = $attr;
                        $current_element['values'][1]['attributes'] = $attributes;
                    }
                } else {
                    $current_element['attributes'] = $attributes;
                }

            } elseif (isset($current_element['values'])) {
                $max_key = max(array_keys($current_element['values']));
                $current_element = &$current_element['values'][$max_key];
            }
        }

        unset($current_element);

        return $this;
    }

    /**
     * Stores value into the result array
     *
     * @param mixed $value Value to be stored
     *
     * @return $this
     */
    protected function storeValue($value = null)
    {
        $path_length = count($this->current_path);
        $current_element = &$this->result;

        foreach ($this->current_path as $key) {
            $path_length--;

            if (isset($current_element[$key])) {
                $current_element = &$current_element[$key];

                if (isset($current_element['values'])) {
                    $maxKey = max(array_keys($current_element['values']));
                    $current_element = &$current_element['values'][$maxKey];
                }
            }

            if ($path_length === 0) {
                $current_element['value'] = $value;
            }
        }

        unset($current_element);

        return $this;
    }

    /**
     * Fetches all keys that have "values" key inside
     *
     * @param array $path Prefix path array
     * @param array $data Data to look in
     *
     * @return array
     */
    protected function getTargetNodes(array $path, $data)
    {
        $result = array();

        foreach ($data as $key => $values) {
            $current_path = $path;
            $current_path[] = $key;

            if (isset($values['values'])) {
                $result[] = $current_path;
                break;
            } elseif (is_array($values) && !isset($values['value'])) {
                unset($values['attributes']);
                $tmp = $this->getTargetNodes($current_path, $values);
                $result = array_merge($result, $tmp);
            }
        }

        return $result;
    }

    /**
     * Fetches path to target node in xml file
     *
     * @return array
     */
    protected function getTargetPath()
    {
        $target_path = array();

        if (!empty($this->options['target_node'])) {
            $target_path = explode(self::TARGET_NODE_PATH_DELIMITER, $this->options['target_node']);
        }

        return $target_path;
    }
}
