<?php

namespace keygenqt\components;

use Yii;
use yii\helpers\Url;
use yii\validators\EmailValidator;

class Html extends \yii\helpers\Html
{
    public static function img($src, $options = [], $default = '')
    {
        $options['src'] = self::imgUrl($src ? $src : $default);

        if (!isset($options['alt'])) {
            $options['alt'] = '';
        }
        return static::tag('img', '', $options);
    }

    public static function imgUrl($src)
    {
        $web = Yii::getAlias('@web');
        if (strpos($src, 'http') === false) {
            $count = count(
                explode('/', str_replace($web, '', Yii::$app->request->getUrl()))
            );
            $path = str_repeat("../", $count - 2);
            $result = Url::to($path . $src);
        } else {
            $result = $src;
        }

        $result .= (YII_ENV_DEV ? '?v=' . time() : '');

        return $result;
    }

    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {

            $options['href'] = Url::to($url);

            if (!is_array($url)) {
                if (is_numeric($url) || preg_match("/^\(\d{3}\)\s\d{3}-\d{4}$/", $url)) {
                    $options['href'] = 'tel:' . preg_replace('/[()\s-]+/ui', '', $url);
                }
                elseif ((new EmailValidator())->validate($url)) {
                    $options['href'] = 'mailto:' . preg_replace('/[()\s-]+/ui', '', $url);
                }
            }
        }
        return static::tag('a', $text, $options);
    }
}