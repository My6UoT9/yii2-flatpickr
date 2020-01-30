<?php

namespace my6uot9\flatpickr;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use my6uot9\flatpickr\assets\FlatpickrAsset;

class FlatpickrWidget extends InputWidget
{
    /**
     * flatpickr
     * @link https://chmln.github.io/flatpickr/
     *
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var string
     */
    public $locale;

    /**
     * @var null|array
     */
    public $plugins = null;

    /**
     * @var string
     */
    public $theme;

    /**
     * Disable input
     *
     * @var bool
     */
    public $disabled = false;

     /**
     * Show clear button
     * @var bool
     */
    public $clear = true;

    /**
     * Show toggle button
     * @var bool
     */
    public $toggle = false;

    /**
     * Buttons
     *
     * @var array
     */
    public $groupBtn = [
        'toggle' => [
            'btnClass' => 'btn btn-outline-secondary',
            'iconClass' => 'fas fa-calendar',
        ],
        'clear' => [
            'btnClass' => 'btn btn-outline-danger',
            'iconClass' => 'fas fa-calendar-times',
        ],
    ];

    /**
     * @return string
     */
    public function run()
    {
        if ($this->toggle || $this->clear) {
            $this->clientOptions['wrap'] = true;
        } else {
            $this->clientOptions['wrap'] = false;
        }

        if ($this->locale === null) {
            $locale = Yii::$app->getLocale();
            $this->locale = $locale->datepicker;
        }

        $this->registerClientScript();
        $content = '';
        $options['data-input'] = '';
        if ($this->disabled) {
            $options['disabled'] = 'disabled';
        }

        if ($this->toggle || $this->clear) {
            $content .= '<div class="flatpickr-' . $this->options['id'] . ' input-group">';

            if ($this->hasModel()) {
                $content .= Html::activeTextInput($this->model, $this->attribute, ArrayHelper::merge($this->options, $options));
            } else {
                $content .= Html::textInput($this->name, $this->value, ArrayHelper::merge($this->options, $options));
            }

            $content .= '<div class="input-group-append">';
            if ($this->toggle) {
                $content .= $this->renderGroupBtn('toggle');
            }
            if ($this->clear) {
                $content .= $this->renderGroupBtn('clear');
            }

            $content .= '</div>';
            $content .= '</div>';
        } else {
            if ($this->hasModel()) {
                $content = Html::activeTextInput($this->model, $this->attribute, ArrayHelper::merge($this->options, $options));
            } else {
                $content = Html::textInput($this->name, $this->value, ArrayHelper::merge($this->options, $options));
            }
        }

        return $content;
    }

    /**
     * Register widget client scripts.
     */
    protected function registerClientScript()
    {
        $this->clientOptions['locale'] = $this->locale;

        if (null === $this->plugins) {
            $this->plugins = [
                'confirmDate' => [
                    'confirmIcon'=> "<i class='fas fa-check'></i>",
                    'confirmText' => '',
                    'showAlways' => true,
                    'theme' =>  $this->theme,
                ]
            ];
        }
        if (!isset($this->clientOptions['allowInput'])){
            $this->clientOptions['allowInput'] = true;
        }
        if (!empty($this->plugins) && is_array($this->plugins)) {
            $plugins = [];
            if (ArrayHelper::keyExists('rangePlugin', $this->plugins)) {
                $options = Json::encode($this->plugins['rangePlugin']);
                $plugins[] = "rangePlugin($options)";
            }
            if (ArrayHelper::keyExists('confirmDate', $this->plugins)) {
                $options = Json::encode($this->plugins['confirmDate']);
                $plugins[] = "confirmDatePlugin($options)";
            }
            if (ArrayHelper::isIn('label', $this->plugins)) {
                $plugins[] = 'labelPlugin()';
            }
            if (ArrayHelper::keyExists('weekSelect', $this->plugins)) {
                $options = Json::encode($this->plugins['weekSelect']);
                $plugins[] = "weekSelectPlugin($options)";
            }

            $this->clientOptions['plugins'] = new JsExpression('[new ' . implode(', ', $plugins) . ']');
        }

        $view = $this->getView();
        $asset = FlatpickrAsset::register($view);

        $asset->locale = $this->locale;
        $asset->plugins = $this->plugins;
        $asset->theme = $this->theme;

        if ($this->toggle || $this->clear) {
            $selector = Json::encode('.flatpickr-' . $this->options['id']);
        } else {
            $selector = Json::encode('#' . $this->options['id']);
        }

        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';

        $view->registerJs("flatpickr($selector, {$options});");
    }

    /**
     * @param string $btnName
     * @return string
     */
    private function renderGroupBtn($btnName)
    {
        $content = '';
        if (isset($this->groupBtn[$btnName])) {
            if (isset($this->groupBtn[$btnName]['btnClass'])) {
                $btnClass = $this->groupBtn[$btnName]['btnClass'];
            } else {
                $btnClass = 'btn btn-default';
            }

            if (isset($this->groupBtn[$btnName]['iconClass'])) {
                $iconClass = $this->groupBtn[$btnName]['iconClass'];
            } else {
                $iconClass = '';
            }

            $disabled = '';
            if ($this->disabled) {
                $disabled = 'disabled="disabled"';
            }

            $content = <<<HTML
                <button class="$btnClass" type="button" $disabled data-$btnName>
                    <span class="$iconClass"></span>
                </button>
HTML;
        }

        return $content;
    }
}
