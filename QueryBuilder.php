<?php

namespace leandrogehlen\querybuilder;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * QueryBuilder renders a jQuery QueryBuilder component.
 *
 * @see http://mistic100.github.io/jQuery-QueryBuilder/
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilder extends  Widget {

    use OptionTrait;

    /**
     * @var bool Allow creation of rules groups
     */
    public $allowGroups;

    /**
     * @var bool Enable sortable rules and groups. Might not work on old browsers not supporting HTML5 Drag & Drop.
     */
    public $sortable;

    /**
     * @var array Array of operators
     */
    public $operators;

    /**
     * @var string[] available conditions
     */
    public $conditions;

    /**
     * @var string Default active condition
     */
    public $defaultCondition;

    /**
     * @var array (key-value pairs)
     */
    public $lang = [];

    /**
     * @var yii\web\JsExpression called when a validation error occurs.
     * Params:
     * - $rule
     * - error
     * - value
     * - filter
     * - operator
     */
    public $onValidationError;

    /**
     * @var yii\web\JsExpression called when a validation error occurs
     * Params:
     * - $group
     */
    public $onAfterAddGroup;

    /**
     * @var yii\web\JsExpression called when a validation error occurs
     * - $rule
     */
    public $onAfterAddRule;

    /**
     * @var array icon configuration. For example:
     * ```php
     * [
     *     'addGroup' => 'glyphicon glyphicon-plus-sign',
     *     'addRule' => 'glyphicon glyphicon-plus',
     *     'removeGroup' => 'glyphicon glyphicon-remove',
     *     'removeRule' => 'glyphicon glyphicon-remove',
     *     'sort' => 'glyphicon glyphicon-sort'
     * ]
     * ```
     */
    public $icons;


    /**
     * @var array filter configuration. Each array element represents the configuration
     * for one particular filter. For example,
     *
     * ```php
     * [
     *     [
     *         'id' => 'name',
     *         'label' => 'Name',
     *         'type' => 'string',
     *     ], [
     *         'id' => 'category',
     *         'label' => 'Category',
     *         'type' => 'integer',
     *         'input' => 'select',
     *         'values' => [
     *             1 => 'Books',
     *             2 => 'Movies',
     *         ]
     *     ]
     * ]
     * ```
     */
    public $filters = [];

    /**
     * Initializes the query builder.
     * This method will instantiate [[filters]] objects and .
     */
    public function init()
    {
        if ($this->icons !== null) {
            $this->icons['class'] = Icon::className();
            $this->icons = Yii::createObject($this->icons);
        }

        $this->initFilters();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();
        $options = Json::encode($this->toOptions());

        echo Html::tag('div', '', ['id' => $id]);

        $view = $this->getView();
        QueryBuilderAsset::register($view);
        $view->registerJs("jQuery('#$id').queryBuilder($options);");
    }

    /**
     * Creates filter objects and initializes them.
     */
    public function initFilters()
    {
        if (empty($this->filters)) {
            throw new InvalidConfigException('The property "filters" does not is empty');
        }
        foreach ($this->filters as $i => $filter) {
            $filter['class'] = Filter::className();
            $this->filters[$i] = Yii::createObject($filter);
        }
    }

} 