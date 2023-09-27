<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/21
 * Time:下午1:19
 * Description:
 */

namespace fjwccy\content;

use ArrayAccess;
use fjwccy\exception\ContentException;

abstract class Content implements ArrayAccess, ContentInterface
{
    protected array $items = [];

    public function toArray(): array
    {
        //格式化数据
        $this->formatData();
        $this->items = $this->objArrFetch($this->items);
        $this->validateData();
        return $this->items;
    }

    private function objArrFetch($arr)
    {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $arr[$k] = $this->objArrFetch($v);
            } elseif ($v instanceof Content) {
                $arr[$k] = $v->toArray();
            } else {
                $arr[$k] = $v;
            }
        }
        return $arr;
    }

    /**
     * @param array $items
     * @throws ContentException
     */
    public function __construct(array $items)
    {
        foreach ($items as $k => $v) {
            $this->offsetSet($k, $v);
        }
    }

    // ArrayAccess
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }


    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @throws ContentException
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset) || !isset($this->items[$offset])) {
            throw new ContentException('无效的报文项' . $offset);
        }
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}