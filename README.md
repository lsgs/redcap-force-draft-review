********************************************************************************
# Force Draft Review

Luke Stevens, Murdoch Children's Research Institute https://www.mcri.edu.au

[https://github.com/lsgs/redcap-force-draft-review/](https://github.com/lsgs/redcap-force-draft-review/)

********************************************************************************
## Summary

Changes the "Submit Changes for Review" button in the Online Designer and Data Dictionary pages to redirect to the "View summary of drafted changes" page. Changes may then be submitted from there. This behaviour prevents users from submitting draft changes without visiting the summary page where potential problems are highlighted.

## Configuration

* This module is designed to be used in the "Enable on all projects" mode, but may be enabled only on individual projects if preferred.
* System Setting: (Optional) Specify preferred text for the "Submit Changes for Review" button across all projects. Suggestion: "View and Submit Changes".
* Project Setting: (Optional) Specify preferred text for the "Submit Changes for Review" button in the current project. (Takes precedence over the system-level setting.)

## Screenshots

### Online Designer / Data Dictionary

The "Submit Changes for Review" button is relabelled, and on clicking will take you to the "Summary of Drafted Changes" page.

<img alt="Online Designer / Data Dictionary button: &quot;View and Submit Changes&quot;" title="Online Designer / Data Dictionary button: &quot;View and Submit Changes&quot;" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=aa67a53abac75b330ba926169c9fc12207e00ec008223b933bd021d8850d574dd93141ad14d4909333a49f4bba88ceb1caa5322bd21246337d88d915b8a70d82&id=2284848&s=35j4Z7gDoGVzzJta&page=file_page&record=28&event_id=47634&field_name=thefile&instance=1" />

### Summary of Drafted Changes

Draft changes may be submitted at the bottom of the "Summary of Drafted Changes" page after reviewing the summary of changes.

<img alt="Review Changes and Submit" title="Review Changes and Submit" src="https://redcap.mcri.edu.au/surveys/index.php?pid=14961&__passthru=DataEntry%2Fimage_view.php&doc_id_hash=764783cf6fbb7761be3c153c002d4d4c04cb793cf6e51a4af6889c62f3928e171bcac5c44da42083a4c95a8b17699cf41d14aff583da7d87a66d2e6066cd4603&id=2284852&s=RWqfzwMAURxH3YUw&page=file_page&record=29&event_id=47634&field_name=thefile&instance=1" />