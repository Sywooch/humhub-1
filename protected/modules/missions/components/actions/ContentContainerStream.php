<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

namespace humhub\modules\missions\components\actions;

use Yii;
use humhub\modules\content\models\Content;
use humhub\modules\missions\components\actions\FixedStream;
use app\modules\missions\models\Evidence;
use humhub\modules\post\models\Post;

/**
 * ContentContainerStreamAction
 * Used to stream contents of a specific a content container.
 *
 * @since 0.11
 * @package humhub.modules_core.wall
 * @author luke
 */
class ContentContainerStream extends FixedStream
{

    public $contentContainer;

    public function init()
    {
        parent::init();

        // Get Content Container by Param
        if ($this->contentContainer->wall_id != "") {
            $this->activeQuery->andWhere("wall_entry.wall_id = " . $this->contentContainer->wall_id);
        } else {
            Yii::warning("No wall id for content container " . get_class($this->contentContainer) . " - " . $this->contentContainer->getPrimaryKey() . " set - stopped stream action!");
            // Block further execution
            $this->activeQuery->andWhere("1=2");
        }

        /**
         * Limit to public posts when no member
         */
        if (!$this->contentContainer->canAccessPrivateContent($this->user)) {
            $this->activeQuery->andWhere("content.visibility=" . Content::VISIBILITY_PUBLIC);
        }

        /**
         * Hide draft
        */
        $this->activeQuery->andFilterWhere(
            ['or',
                ['content.object_model' => Post::className()],
                ['and',
                    ['content.object_model' => Evidence::className()],
                    ['or',
                       ['content.visibility' => 1],
                       ['content.user_id' => Yii::$app->user->getIdentity()->id]
                    ]
                ]       
            ]);

        /**
         * Handle sticked posts only in content containers
         */
        if ($this->limit != 1) {
            if ($this->from == '') {
                $oldOrder = $this->activeQuery->orderBy;
                $this->activeQuery->orderBy("");

                $this->activeQuery->addOrderBy('content.sticked DESC');
                $this->activeQuery->addOrderBy($oldOrder);
            } else {
                $this->activeQuery->andWhere("(content.sticked != 1 OR content.sticked is NULL)");
            }
        }
    }

}
