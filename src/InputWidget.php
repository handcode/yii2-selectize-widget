<?php
/**
 * @link https://github.com/2amigos/yii2-selectize-widget
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\selectize;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * InputWidget
 *
 * @author 2amigos.us <hola@2amigos.us>
 */
class InputWidget extends \yii\widgets\InputWidget
{
    /**
     * @var string
     */
    public $loadUrl;
    
    /**
     * @var string the parameter name
     */
    public $queryParam = 'query'; 

    /**
     * @var array
     */
    public $clientOptions;

    public $cssFlavour = 'default';
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];

        if ($this->loadUrl !== null) {
            $url = Url::to($this->loadUrl);
            $this->clientOptions['load'] = new JsExpression("function (query, callback) { if (!query.length) return callback(); $.getJSON('$url', { {$this->queryParam}: query }, function (data) { callback(data); }).fail(function () { callback(); }); }");
        }

        $options = Json::encode($this->clientOptions);
        $view = $this->getView();
        $assetBundle = SelectizeAsset::register($view);
        switch ($this->cssFlavour) {
            case 'bs3':
            case 'bootstrap3':
            $themeCss = ['css/selectize.bootstrap3.css'];
            break;
            case 'bs4':
            case 'bootstrap4':
                $themeCss = ['css/selectize.bootstrap4.css'];
                break;
            case 'bs5':
            case 'bootstrap5':
                $themeCss = ['css/selectize.bootstrap5.css'];
                break;
            default:
                $themeCss = ['css/selectize.default.css'];
            break;
        }
        $assetBundle->css = ArrayHelper::merge($assetBundle->css, $themeCss);

        $view->registerJs("jQuery('#$id').selectize($options);");
    }
}
