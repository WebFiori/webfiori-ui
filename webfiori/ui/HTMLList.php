<?php
/**
 * This file is licensed under MIT License.
 *
 * Copyright (c) 2018 Ibrahim BinAlshikh
 *
 * For more information on the license, please visit:
 * https://github.com/WebFiori/.github/blob/main/LICENSE
 *
 */
namespace webfiori\ui;

/**
 * A class that represents ordered list or unordered list.
 *
 * @author Ibrahim
 */
class HTMLList extends HTMLNode {
    /**
     * Creates new instance of the class.
     * 
     * @param string $listType A string that represents list type. It can have 
     * two values, 'ul' or 'li'. Default is 'ul'.
     * 
     * @param array $arrOfItems An array that contains strings 
     * that represents each list item. Also, it can have objects of type 
     * 'ListItem' or 'HTMLNode'.
     * 
     * @param bool $escHtmlEntities If set to true, the method will 
     * replace the characters '&lt;', '&gt;' and 
     * '&amp' with the following HTML entities: '&amp;lt;', '&amp;gt;' and '&amp;amp;' 
     * in the given text. Default is true.
     * 
     * @since 1.0
     */
    public function __construct(string $listType = 'ul', array $arrOfItems = [], bool $escHtmlEntities = true) {
        parent::__construct('ul');
        $lower = strtolower(trim($listType));

        if ($lower == 'ol') {
            $this->setNodeName('ol');
        }
        $this->addListItems($arrOfItems, $escHtmlEntities);
    }
    /**
     * Adds new child node.
     * 
     * @param ListItem|HTMLNode|string $node The node that will be added. 
     * It can be an instance of the class 'ListItem', instance of the 
     * class 'HTMLNode' or a string that represents the 
     * content of the list item that will be added.
     * 
     * @param array|bool $attrsOrChain An optional array of attributes which will be set in
     * the newly added list item.
     * 
     * @param bool $chainOnParent If this parameter is set to true, the method 
     * will return the same instance at which the child node is added to. If 
     * set to false, the method will return the child which have been added. 
     * This can be useful if the developer would like to add a chain of elements 
     * to the body of the parent or child. Default value is false. It means the 
     * chaining will happen at child level.
     * 
     * @return ListItem|HTMLList If the parameter <code>$chainOnParent</code> is set to true, 
     * the method will return the '$this' instance. If set to false, it will 
     * return the newly added child.
     * 
     * @throws InvalidNodeNameException The method will throw this exception if 
     * node name is given and the name is not valid.
     * 
     * @since 1.0
     */
    public function addChild($node, $attrsOrChain = [], bool $chainOnParent = false) {
        if ($node instanceof ListItem) {
            return parent::addChild($node, $attrsOrChain, $chainOnParent);
        } else {
            $li = new ListItem();

            if ($node instanceof HTMLNode) {
                $li->addChild($node);
            } else {
                $li->text($node.'');
            }

            return parent::addChild($li, $attrsOrChain, $chainOnParent);
        }
    }
    /**
     * Adds new item to the list.
     * 
     * @param string|ListItem|HTMLNode $listItemBody The text that will be displayed by the 
     * list item. Also, it can be an object of type 'HTMLNode'.
     * 
     * @param bool $escHtmlEntities If set to true and the body of the list is a text, 
     * the method will replace the characters '&lt;', '&gt;' and 
     * '&amp' with the following HTML entities: '&amp;lt;', '&amp;gt;' and '&amp;amp;' 
     * in the given text. Applicable only if the first parameter is a text. 
     * Default is true.
     * 
     * @return HTMLList The method will return the instance at which the method 
     * is called on.
     * 
     * @since 1.0
     */
    public function addListItem($listItemBody, bool $escHtmlEntities = true) : HTMLList {
        if ($listItemBody instanceof ListItem) {
            $this->addChild($listItemBody);
        } else {
            $li = new ListItem(null);

            if ($listItemBody instanceof HTMLNode) {
                $li->addChild($listItemBody);
            } else {
                $li->text($listItemBody.'',$escHtmlEntities);
            }
            $this->addChild($li);
        }

        return $this;
    }
    /**
     * Adds multiple items at once to the list.
     * 
     * @param array $arrOfItems An array that contains strings 
     * that represents each list item. Also, it can have objects of type 
     * 'ListItem' or 'HTMLNode'.
     * 
     * @param bool $escHtmlEntities If set to true and a text is given for the list 
     * item, the method will replace the characters '&lt;', '&gt;' and 
     * '&amp' with the following HTML entities: '&amp;lt;', '&amp;gt;' and '&amp;amp;' 
     * in the given text. Default is true.
     * 
     * @return HTMLList The method will return the instance at which the method 
     * is called on.
     * 
     * @since 1.0.1
     * 
     */
    public function addListItems(array $arrOfItems, bool $escHtmlEntities = true) : HTMLList {
        if (gettype($arrOfItems) == 'array') {
            foreach ($arrOfItems as $listItem) {
                $this->addListItem($listItem,$escHtmlEntities);
            }
        }

        return $this;
    }
    /**
     * Adds a sub-list to the main list.
     * 
     * @param HTMLList $ul An object of type 'HTMLList'.
     * 
     * @return HTMLList The method will return the instance at which the method 
     * is called on.
     * 
     * @since 1.0
     */
    public function addSubList(HTMLList $ul) : HTMLList {
        $li = new ListItem();
        $li->addList($ul);
        $this->addChild($li);

        return $this;
    }
    /**
     * Returns a child node given its index.
     * 
     * @param int $index The position of the child node. This must be an integer 
     * value starting from 0.
     * 
     * @return ListItem|null If the child does exist, the method will return an 
     * object of type 'ListItem'. If no element was found, the method will 
     * return null.
     * 
     * @since 1.0.2
     */
    public function getChild(int $index) {
        $ch = parent::getChild($index);

        if ($ch instanceof ListItem) {
            return $ch;
        }

        return null;
    }
}
