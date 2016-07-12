<?php

namespace app\modules\missions\widgets;

use Yii;
use yii\helpers\Url;

/**
 * Wall Stream Widget creates a wall widget.
 *
 * @package humhub.modules_core.wall.widgets
 * @since 0.11
 */
class EvokationStream extends \yii\base\Widget
{

    /**
     * Optional content container if this stream belongs to one
     *
     * @var HActiveRecordContentContainer
     */
    public $contentContainer;

    /**
     * Path to Stream Action to use
     *
     * @var string
     */
    public $streamAction = "";

    /**
     * Show default wall filters
     *
     * @var boolean
     */
    public $showFilters = true;

    /**
     * @var array filters to show
     */
    public $filters = [];

    /**
     * Message when stream is empty and filters are active
     *
     * @var string
     */
    public $messageStreamEmptyWithFilters = "";

    /**
     * CSS Class(es) for empty stream error with enabled filters
     *
     * @var string
     */
    public $messageStreamEmptyWithFiltersCss = "";

    /**
     * Message when stream is empty
     *
     * @var string
     */
    public $messageStreamEmpty = "";

    /**
     * CSS Class(es) for message when stream is empty
     *
     * @var string
     */
    public $messageStreamEmptyCss = "";


    public $activity_id;

    /**
     * Inits the Wall Stream Widget
     */
    public function init()
    {
        if ($this->streamAction == "") {
            throw new \yii\web\HttpException(500, 'You need to set the streamAction attribute to use this widget!');
        }

        // Add default Filters
        if (count($this->filters) === 0) {
            $this->filters['filter_entry_userinvoled'] = Yii::t('ContentModule.widgets_views_stream', 'Where I´m involved');
            $this->filters['filter_entry_mine'] = Yii::t('ContentModule.widgets_views_stream', 'Created by me');
            $this->filters['filter_entry_files'] = Yii::t('ContentModule.widgets_views_stream', 'Content with attached files');
            $this->filters['filter_posts_links'] = Yii::t('ContentModule.widgets_views_stream', 'Posts with links');
            $this->filters['filter_model_posts'] = Yii::t('ContentModule.widgets_views_stream', 'Posts only');
            $this->filters['filter_entry_archived'] = Yii::t('ContentModule.widgets_views_stream', 'Include archived posts');
            $this->filters['filter_visibility_public'] = Yii::t('ContentModule.widgets_views_stream', 'Only public posts');
            $this->filters['filter_visibility_private'] = Yii::t('ContentModule.widgets_views_stream', 'Only private posts');
        }


        /**
         * Setup default messages
         */
        if ($this->messageStreamEmpty == "") {
            $this->messageStreamEmpty = Yii::t('ContentModule.widgets_views_stream', 'Nothing here yet!');
        }
        if ($this->messageStreamEmptyWithFilters == "") {
            $this->messageStreamEmptyWithFilters = Yii::t('ContentModule.widgets_views_stream', 'No matches with your selected filters!');
        }
    }

    /**
     * Creates url to stream BaseStreamAction including placeholders
     * which are replaced and handled by javascript.
     *
     * If a contentContainer is specified it will be used to create the url.
     *
     * @return string
     */
    protected function getStreamUrl()
    {
        $params = [
            $this->streamAction,
            'limit' => '-limit-',
            'filters' => '-filter-',
            'sort' => '-sort-',
            'from' => '-from-',
            'activity_id' => $this->activity_id,
            'mode' => \humhub\modules\content\components\actions\Stream::MODE_NORMAL
        ];

        if ($this->contentContainer) {
            return $this->contentContainer->createUrl($this->streamAction, $params);
        }

        return Url::to($params);
    }

    /**
     * Creates the Wall Widget
     */
    public function run()
    {
        return $this->render('stream', array('streamUrl' => $this->getStreamUrl(), 'showFilters' => $this->showFilters, 'filters' => $this->filters));
    }

}
?>