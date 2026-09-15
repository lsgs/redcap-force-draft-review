<?php
/**
 * Force Draft Review
 * Changes the "Submit Draft Changes" button in the Online Designer and Data Dictionary pages to redirect to the "View summary of drafted changes" page. 
 * Changes may then be submitted from there.
 * @author Luke Stevens, Murdoch Children's Research Institute
 */
namespace MCRI\ForceDraftReview;

use ExternalModules\AbstractExternalModule;

class ForceDraftReview extends AbstractExternalModule
{
    public function redcap_every_page_top(mixed $project_id) {
        $project_id = intval($project_id);
        if (empty($project_id)) return;
        if (!defined('PAGE')) return;
        if (PAGE=='Design/online_designer.php') {
            $this->online_designer_page_top($project_id);
        } else if (PAGE=='Design/data_dictionary_upload.php' && !isset($_POST['filepath'])) { // skip for the file upload step as no Submit from summary page
            $this->data_dictionary_page_top($project_id);
        } else if (PAGE=='Design/project_modifications.php' && isset($_GET['allow_submit']) && $_GET['allow_submit'] == '1') {
            $this->modifications_page_top($project_id);
        }
    }

    protected function online_designer_page_top(int $project_id): void {
        $this->designer_page_top($project_id, 'Design/online_designer.php');
    }

    protected function data_dictionary_page_top(int $project_id): void {
        $this->designer_page_top($project_id, 'Design/data_dictionary_upload.php');
    }

    protected function designer_page_top(int $project_id, string $page_ref): void {
        $override_text_system = $this->getSystemSetting('submit-btn-text-s');
        $override_text_project = $this->getProjectSetting('submit-btn-text-p');
        if (!empty($override_text_project)) {
            $button_label = $override_text_project;
        } else if (!empty($override_text_system)) {
            $button_label = $override_text_system;
        } else {
            $button_label = \RCView::tt('design_255');
        }
        global $lang;
        $lang['design_255'] = \REDCap::filterHtml($button_label);

        $review_page_url = \REDCap::filterHtml("project_modifications.php?pid=$project_id&allow_submit=1&ref=".urlencode($page_ref));
        $this->initializeJavascriptModuleObject();
        ?>
        <!-- Force Draft Review: start -->
        <script type="text/javascript">
            $(function(){
                var module = <?=$this->getJavascriptModuleObjectName()?>;
                module.submit_changes_btn = $('input[role=button]:first');
                module.review_page_url = '<?=$review_page_url?>';

                module.init = function() {
                    module.submit_changes_btn.removeAttr('onclick');
                    module.submit_changes_btn.click(function(e){
                        e.preventDefault();
                        window.location.href = module.review_page_url;
                    });
                };

                $(document).ready(function(){ module.init(); });
            });
        </script>
        <!-- Force Draft Review: end -->
        <?php
    }

    protected function modifications_page_top(int $project_id): void {
        global $auto_prod_changes, $status;
        if (intval($status) != 1) return;

        $auto_prod_txt = ($auto_prod_changes > 0) ? "<div style='background:#f5f5f5;border:1px solid #ddd;padding:4px;'>".\RCView::tt('design_287',false)."</div>" : "<br>";
        $multi_lang_txt = (\MultiLanguageManagement\MultiLanguage::isActive($project_id) && \MultiLanguageManagement\MultiLanguage::hasLanguages($project_id)) ? \RCView::div(['class'=>'yellow mt-3'], '<i class="fas fa-globe"></i> ' . \RCView::tt('multilang_223')) : "";
        $this->initializeJavascriptModuleObject();
        ?>
        <!-- Force Draft Review: start -->
        <style type="text/css">
            #fdr-submit-btn-container { display: none; margin-top: 2em; margin-left: 1em; }
            #fdr-confirm-review { display: none; }
        </style>
        <span id="fdr-submit-btn-container"><button type="button" id="fdr-submit-btn" class=jqbutton fs14><i class="fa-solid fa-circle-check"></i> <?= \RCView::tt('design_255',false) ?></button></span>
        <div id="fdr-confirm-review" title="<?= \js_escape2(\RCView::tt('design_16',false)) ?>">
            <p><?= \RCView::tt('design_17',false) ?>
                <?= $auto_prod_txt ?>
                <?= $multi_lang_txt ?>
                <br>
                <img src=" <?= APP_PATH_IMAGES . 'star.png' ?>"> <?= \RCView::tt('edit_project_55',false) ?>
                <a style="text-decoration:underline;" href="<?= APP_PATH_WEBROOT."index.php?pid=$project_id&route=IdentifierCheckController:index" ?>"> <?= \RCView::tt('identifier_check_01',false)."</a> ".\RCView::tt('edit_project_56',false) ?>
            </p>
        </div>
        <script type="text/javascript">
            $(function(){
                var module = <?=$this->getJavascriptModuleObjectName()?>;
                module.return_prev_page_btn = $('button:has(span[data-rc-lang="config_functions_40"])').eq(1); // only the second button - bottom of page - which is not present when there are no changes
                module.submit_changes_btn = $('#fdr-submit-btn');

                module.submit_changes = function() {
                    $('#fdr-confirm-review').dialog({ bgiframe: true, modal: true, width: 600, buttons: {
                        '<?= js_escape(\RCView::tt('global_53',false))?>': function() { $(this).dialog('close'); },
                        '<?= js_escape(\RCView::tt('survey_200',false))?>': function() {
                            $('#fdr-confirm-review').parent().find('.ui-dialog-buttonpane button:eq(1)').html('<?= js_escape(\RCView::tt('design_160',false)) ?>');
                            $('#fdr-confirm-review').parent().find('.ui-dialog-buttonpane button:eq(0)').css('display','none');
                            showProgress(1);
                            window.location.href=app_path_webroot+'Design/draft_mode_review.php?pid='+pid;
                        }
                    } });
                };

                module.init = function() {
                    if (module.return_prev_page_btn.length) {
                        $('#fdr-submit-btn-container').insertAfter(module.return_prev_page_btn).show();
                        $(module.submit_changes_btn).on('click', module.submit_changes);
                    }
                };

                $(document).ready(function(){ module.init(); });
            });
        </script>
        <!-- Force Draft Review: end -->
        <?php
    }
}