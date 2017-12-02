<?php 

namespace Clover;

class Helper
{
	/**
	* @link http://foundation-datepicker.peterbeno.com/example.html#docs
	* @param $optins: {
		format	string	'mm/dd/yyyy'	the date format, combination of d, dd, m, mm, yy, yyyy, hh, ii.
		language	string	'en'	two-char iso shortcut of language you want to use
		weekStart	integer	0	day of the week start. 0 for Sunday - 6 for Saturday
		startView	string|integer	month	set the start view mode. Accepts: 'decade' = 4, 'year' = 3, 'month' = 2, 'day' = 1, 'hour' = 0
		minView|maxView	string|integer		set a limit for view mode. Accepts: 'decade' = 4, 'year' = 3, 'month' = 2, 'day' = 1, 'hour' = 0
		pickTime	boolean	false	enables hour and minute selection views, equivalent of minView = 0, else minView = 2
		initialDate	date string	null	sets initial date. The same effect can be achieved with value attribute on input element.
		startDate	date	-Infinity	disables all dates before given date
		endDate	date	Infinity	disables all dates after given date
		keyboardNavigation	boolean	true	with input fields, allows to navigate the datepicker with arrows. However, it disables navigation inside the input itself, too
		daysOfWeekDisabled	Array of integers	[]	disables all dates matching the given days of week (0 = Sunday, 6 = Saturday)
		datesDisabled	Array of date strings	[]	disables the specified dates
	}
	**/
	public static function date($text, $attribute, $format = 'yyyy-mm-dd', $pickTime = false, $startView = 'year') 
	{
		return self::input($text, $attribute, 'date', true, [
			'format' => $format,
            'pickTime' => $pickTime,
            'startView' => $startView
		]);
	}

	public static function select($text, $attribute, $options, $type = 'select', $alerclass = true) 
	{
		$input = self::input($text, $attribute, $type, $alerclass);

		if ($options instanceof Collection) {
			$input['options'] = $options->map(function($item) {
				return $item->only('id', 'name');
			})->prepend(['id' => '', 'name' => ''])
				->toArray();
		}else {
			$input['options'] = $options;
		}
		

		return $input;

	}


	public static function input($text, $attribute, $type = 'text', $alerclass = true, ...$params) 
	{
		return [
			'text' => $text, 
			'attribute' => $attribute, 
			'type' => $type,
			'params' => $params,
			'alerclass' => $alerclass
		];
	}

	/**
	* Clover combine
	* the combine method it splits $values into the $keys length of group
	* then combines the $keys with the group
	* @param $keys as Array
	* @param $values as Array / Collection
	* @return Array
	*/
	public static function combine($keys, ...$values) 
	{
		$values = array_flatten($values);

		$collected = 
			$values instanceof \Illuminate\Support\Collection || 
			$values instanceof Collection? 
			$values: collect($values);

		$columns = ceil(count($values)/count($keys));

		return $collected
			->split($columns)
			->map(function($value) use ($keys) {
				return array_combine($keys, $value->toArray());
			})
			->toArray();

	}
}