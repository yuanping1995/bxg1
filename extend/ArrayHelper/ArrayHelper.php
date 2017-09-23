<?php

namespace ArrayHelper;
class ArrayHelper {
	/**
	 * 从数组中删除空白的元素（包括只有空白字符的元素）
	 *
	 * 用法：
	 * @code php
	 * $arr = array('', 'test', '  ');
	 * ArrayHelper::removeEmpty($arr);
	 *
	 * dump($arr);
	 *  // 输出结果中将只有 'test'
	 * @endcode
	 *
	 * @param array $arr 要处理的数组
	 * @param boolean $trim 是否对数组元素调用 trim 函数
	 */
	static function removeEmpty(&$arr, $trim = TRUE) {
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				self::removeEmpty($arr[$key]);
			} else {
				$value = trim($value);
				if ($value == '') {
					unset($arr[$key]);
				} elseif ($trim) {
					$arr[$key] = $value;
				}
			}
		}
	}

	/**
	 * 从一个二维数组中返回指定键的所有值
	 * @endcode
	 *
	 * @param array $arr 数据源
	 * @param string $col 要查询的键
	 *
	 * @return array 包含指定键所有值的数组
	 */
	static function getCols($arr, $col) {
		$ret = array();
		foreach ($arr as $row) {
			if (isset($row[$col])) {
				$ret[] = $row[$col];
			}
		}
		return $ret;
	}


	static function toHashmap($arr, $keyField, $valueField = NULL) {
		$ret = array();
		if ($valueField) {
			foreach ($arr as $row) {
				$ret[$row[$keyField]] = $row[$valueField];
			}
		} else {
			foreach ($arr as $row) {
				$ret[$row[$keyField]] = $row;
			}
		}
		return $ret;
	}

	/**
	 * 将一个二维数组按照指定字段的值分组
	 *
	 * 用法：
	 * @endcode
	 *
	 * @param array $arr 数据源
	 * @param string $keyField 作为分组依据的键名
	 *
	 * @return array 分组后的结果
	 */
	static function groupBy($arr, $keyField) {
		$ret = array();
		foreach ($arr as $row) {
			$key = $row[$keyField];
			$ret[$key][] = $row;
		}
		return $ret;
	}


	static function toTree($arr, $keyNodeId, $keyParentId = 'parent_id', $keyChildrens = 'childrens', &$refs = NULL) {
		$refs = array();
		foreach ($arr as $offset => $row) {
			$arr[$offset][$keyChildrens] = array();
			$refs[$row[$keyNodeId]] = &$arr[$offset];
		}
		$tree = array();
		foreach ($arr as $offset => $row) {
			$parentId = $row[$keyParentId];
			if ($parentId) {
				if (!isset($refs[$parentId])) {
					$tree[] = &$arr[$offset];
					continue;
				}
				$parent = &$refs[$parentId];
				$parent[$keyChildrens][] = &$arr[$offset];
			} else {
				$tree[] = &$arr[$offset];
			}
		}
		return $tree;
	}



	static function sortByCol($array, $keyname, $dir = SORT_ASC) {
		return self::sortByMultiCols($array, array($keyname => $dir));
	}

	/**
	 * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
	 *
	 * 用法：
	 * @code php
	 * $rows = ArrayHelper::sortByMultiCols($rows, array(
	 *   'parent' => SORT_ASC,
	 *   'name' => SORT_DESC,
	 * ));
	 * @endcode
	 *
	 * @param array $rowset 要排序的数组
	 * @param array $args 排序的键
	 *
	 * @return array 排序后的数组
	 */
	static function sortByMultiCols($rowset, $args) {
		$sortArray = array();
		$sortRule = '';
		foreach ($args as $sortField => $sortDir) {
			foreach ($rowset as $offset => $row) {
				$sortArray[$sortField][$offset] = $row[$sortField];
			}
			$sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
		}
		if (empty($sortArray) || empty($sortRule)) {
			return $rowset;
		}
		eval('array_multisort(' . $sortRule . '$rowset);');
		return $rowset;
	}

}
