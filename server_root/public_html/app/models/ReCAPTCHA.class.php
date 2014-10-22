<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 4:56 PM
 */
class ReCAPTCHA
{
	const PRODUCTION_HOST = "sass.zz.vc";
	const PUBLIC_KEY_PRODUCTION = "6LfJdfwSAAAAALXGS51frGnu00x4JY_ZM3SZ_BTa";
	const PRIVATE_KEY_PRODUCTION = "6LfJdfwSAAAAACrOeMejHsg6kNNIdAeJ9sKSHR9l";

	const STAGE_HOST = "dev-sass.hol.es";
	const PUBLIC_KEY_STAGE = "6Lf0bPoSAAAAAEKxZ8WB9OxakwBS2ZMM1N8ptIDD";
	const PRIVATE_KEY_STAGE = "6Lf0bPoSAAAAAOKk2ABa7A5Tca5pI65DdiUq3IJI";

	const RDOK_HOST = "sass.app";
	const PUBLIC_KEY_RDOK = "6LeDbPoSAAAAAK5ZbtC2g5fMlT_oqh1PbdM_BUhX";
	const PRIVATE_KEY_RDOK = "6LeDbPoSAAAAAEEmGB-M8kAzSNPU1Ld1zBTF3k1k";

	const GEOIF_HOST = "sass-ms";
	const PUBLIC_KEY_GEOIF = "6LeJcPoSAAAAALXLpLdq-WiNXJp2W6dwwkk4N8G7";
	const PRIVATE_KEY_GEOIF = "6LeJcPoSAAAAALH7cUOxmyBgr34tHmFFseOJCd7F";


	public static function retrievePublicKey() {
		$currentHost = $_SERVER['SERVER_NAME'];

		if (strcmp($currentHost, self::PRODUCTION_HOST) === 0) return self::PUBLIC_KEY_PRODUCTION;
		if (strcmp($currentHost, self::STAGE_HOST) === 0) return self::PUBLIC_KEY_STAGE;
		if (strcmp($currentHost, self::RDOK_HOST) === 0) return self::PUBLIC_KEY_RDOK;
		if (strcmp($currentHost, self::GEOIF_HOST) === 0) return self::PUBLIC_KEY_GEOIF;
	}

	public static function retrievePrivateKey() {
		$currentHost = $_SERVER['SERVER_NAME'];

		if (strcmp($currentHost, self::PRODUCTION_HOST) === 0) return self::PRIVATE_KEY_PRODUCTION;
		if (strcmp($currentHost, self::STAGE_HOST) === 0) return self::PRIVATE_KEY_STAGE;
		if (strcmp($currentHost, self::RDOK_HOST) === 0) return self::PRIVATE_KEY_RDOK;
		if (strcmp($currentHost, self::GEOIF_HOST) === 0) return self::PRIVATE_KEY_GEOIF;
	}
}