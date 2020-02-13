<?php
/**
 * ArticleController.php - Main Controller
 *
 * Main Controller Article Module
 *
 * @category Controller
 * @package Article
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Article\Filter\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Article\Model\Article;
use OnePlace\Article\Model\ArticleTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class FilterController extends CoreEntityController {
    /**
     * Article Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * ArticleController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param ArticleTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,ArticleTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'article-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    public function filterIndexByState() {
        /**
         * Execute your hook code here
         *
         * optional: return an array to attach data to View
         * otherwise return true
         */
        # Get State Tag
        $oTag  = CoreEntityController::$aCoreTables['core-tag']->select([
            'tag_key'=>'state',
        ]);

        if(count($oTag) > 0) {
            $oTag = $oTag->current();

            # Get Entity Tag for State Filter "available"
            $oEntityTag = CoreEntityController::$aCoreTables['core-entity-tag']->select([
                'tag_value'=>'available',
                'entity_form_idfs'=>'article-single',
                'tag_idfs'=>$oTag->Tag_ID,
            ]);
            if(count($oEntityTag) > 0) {
                $oEntityTag = $oEntityTag->current();
                $oPaginator = $this->oTableGateway->fetchAll(true,['state_idfs'=>$oEntityTag->Entitytag_ID]);
                return $oPaginator;
            }
        }

        # $oPaginator = $this->oTableGateway->fetchAll(true,['created_by'=>CoreEntityController::$oSession->oUser->getID()]);

        return [];
    }
}
