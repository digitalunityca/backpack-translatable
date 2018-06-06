<?php
namespace DigitalUnityCa\Translatable\App\Factory\Provider;

use Faker\Provider\Base;

class Metas extends Base
{
    /**
     * Return meta title
     * @return string
     */
    public function metaTitle(): string
    {
        return $this->generator->text(140);
    }

    /**
     * Return meta description
     * @return string
     */
    public function metaDescription(): string
    {
        return $this->generator->text(260);
    }

    /**
     * Return share title
     * @return string
     */
    public function shareTitle(): string
    {
        return $this->metaTitle();
    }

    /**
     * Return share description
     * @return string
     */
    public function shareDescription(): string
    {
        return $this->metaDescription();
    }

    /**
     * Return share image
     * @return string
     */
    public function shareImage(): string
    {
        return $this->generator->imageUrl(300, 300, 'food', true, 'restaurant');
    }
}