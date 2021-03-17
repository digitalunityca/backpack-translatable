<?php
namespace DigitalUnityCa\Translatable\App\Factory\Provider;

use Faker\Provider\Base;
use \Imvescor\Geo\App\Models\City;

class Social extends Base
{

    const SOCIAL_URL = [
        'facebook'  => 'https://facebook.com/',
        'twitter'   => 'https://twitter.com/',
        'instagram' => 'https://instagram.com/',
        'youtube'   => 'https://youtube.com/',
        'google'    => 'https://google.com/',
        'flickr'    => 'https://flickr.com/',
    ];

    /**
     * Return social page
     * @return string
     */
    public function social(string $provider): string
    {
        if (!in_array($provider,array_keys(self::SOCIAL_URL))){
            throw new \InvalidArgumentException('Provider not found');
        }
        return strtolower(self::SOCIAL_URL[$provider].$this->accountName());
    }

    /**
     * Generate a slug name
     * @return string
     */
    public function accountName(): string
    {
        return str_slug($this->generator->name);
    }
}