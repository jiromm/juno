<?php

namespace Config\Constant;

class DBData {
	private static $therapies = [
		'1' => 'Արտ-թերապիա',
		'2' => 'Հիպոթերապիա',
		'3' => 'Հիդրոթերապիա',
	];

	private static $districts = [
		'1' => 'Աջափնյակ',
		'2' => 'Ավան',
		'3' => 'Արաբկիր',
		'4' => 'Դավթաշեն',
		'5' => 'Էրեբունի',
		'6' => 'Կենտրոն',
		'7' => 'Մալաթիա - Uեբաuտիա',
		'8' => 'Նոր Նորք',
		'9' => 'Նորք - Մարաշ',
		'10' => 'Նուբարաշեն',
		'11' => 'Շենգավիթ',
		'12' => 'Քանաքեռ - Զեյթուն',
	];

	private static $regions = [
		'1' => 'Արագածոտն',
		'2' => 'Արարատ',
		'3' => 'Արմավիր',
		'4' => 'Գեղարքունիք',
		'5' => 'Լոռի',
		'6' => 'Կոտայք',
		'7' => 'Շիրակ',
		'8' => 'Uյունիք',
		'9' => 'Վայոց ձոր',
		'10' => 'Տավուշ',
		'11' => 'Երևան',
	];

	private static $disease = [
		'0' => '-- անհայտ --',
		'1' => 'Հեմիպլեգիա',
		'2' => 'Դիպլեգիա',
		'3' => 'Տռիպլեգիա',
		'4' => 'Կվատրիպլեգիա',
	];

	public static function getDistricts() {
		return self::$districts;
	}

	public static function getDistrict($districtId) {
		if (isset(self::$districts[$districtId])) {
			return self::$districts[$districtId];
		}

		throw new \Exception('Requested district is not exists.');
	}

	public static function getRegions() {
		return self::$regions;
	}

	public static function getRegion($regionId) {
		if (isset(self::$regions[$regionId])) {
			return self::$regions[$regionId];
		}

		throw new \Exception('Requested region is not exists.');
	}

	public static function getDiseases() {
		return self::$disease;
	}

	public static function getDisease($diseaseId) {
		if (isset(self::$disease[$diseaseId])) {
			return self::$disease[$diseaseId];
		}

		throw new \Exception('Requested disease is not exists.');
	}

	public static function getTherapies() {
		return self::$therapies;
	}

	public static function getTherapy($therapyId) {
		if (isset(self::$therapies[$therapyId])) {
			return self::$therapies[$therapyId];
		}

		throw new \Exception('Requested therapy is not exists.');
	}
}
