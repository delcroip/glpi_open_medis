<?php
/**
 -------------------------------------------------------------------------
  LICENSE

 This file is part of openMEDIS plugin for GLPI.

 Reports is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 openMEDIS is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Reports. If not, see <http://www.gnu.org/licenses/>.

 @package   openmedis
 @authors   Patrick Delcroix
 @copyright Copyright (c) 2009-2021 Reports plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://github.com/delcroip/glpi_open_medis
 @link      http://www.glpi-project.org/
 @since     2021
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/// Class PluginOpenmedisMedicalAccessoryCategory
class PluginOpenmedisMedicalAccessoryCategory extends CommonTreeDropdown {

   public $can_be_translated = true;
  // public $must_be_replace              = true;
   public $dohistory                    = true;

   static $rightname                    = 'plugin_openmedis_medicalaccessorycategory';


   static function getTypeName($nb = 0) {
      return _n('Medical device category (e.g. UMDS,GMDN)', 'Medical device categories (e.g. UMDS,GMDN)', $nb);
   }


   function getAdditionalFields() {

      $tab = [['name'      => 'code',
      'label'     => __('Code'),
      'type'      => 'text',
      'list'      => true],
      ['name'      => 'label',
      'label'     => __('Label'),
      'type'      => 'text',
      'list'      => true],
      
            ['name'      => 'plugin_openmedis_medicalaccessorycategories_id',
                         'label'     => __('Parent'),
                         'type'      => 'dropdownValue',
                         'permit_select_parent' => true,
                         'displaywith' => ['code','label']],
         ['name'      => 'picture',
                         'label'     => __('Picture'),
                         'type'      => 'picture'],
                  ];

      if (!Session::haveRightsOr(PluginOpenmedisMedicalAccessoryCategory::$rightname, [CREATE, UPDATE, DELETE])) {

         unset($tab[7]);
      }
      return $tab;

   }
   function rawSearchOptions() {
      $tab                       = [];
      $tab =[[
         'id'                 => '60',
         'table'              => $this->getTable(),
         'field'              => 'code',
         'name'               => __('Code'),
         'datatype'           => 'itemlink',
         'right'              => PluginOpenmedisMedicalAccessoryCategory::$rightname
      ],[
         'id'                 => '80',
         'table'              => $this->getTable(),
         'field'              => 'label',
         'name'               => __('Label'),
         'datatype'           => 'text',
         'right'              => PluginOpenmedisMedicalAccessoryCategory::$rightname
      ],[
         'id'                 => '100',
         'table'              => $this->getTable(),
         'field'              => 'comment',
         'name'               => __('Comment'),
         'datatype'           => 'text',
         'right'              => PluginOpenmedisMedicalAccessoryCategory::$rightname
      ]];

      return $tab;
   }


   // taken fron drop down without name
   function showForm($ID, $options = []) {
      global $CFG_GLPI;

      if (!$this->isNewID($ID)) {
         $this->check($ID, READ);
      } else {
         // Create item
         $this->check(-1, CREATE);
      }
      $this->showFormHeader($options);

      $fields = $this->getAdditionalFields();
      $nb     = count($fields);

      echo "<tr class='tab_bg_1'><td></td>";
      echo "<td>";
      
      echo "</td>";

      echo "<td rowspan='".($nb+1)."'>". __('Comments')."</td>";
      echo "<td rowspan='".($nb+1)."'>
            <textarea cols='45' rows='".($nb+2)."' name='comment' >".$this->fields["comment"];
      echo "</textarea></td>";



      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Code')."</td>\n";
      echo "<td>";
      Html::autocompletionTextField($this, "code");
      echo "</td>\n";
      echo "</tr>\n";

      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Label')."</td>\n";
      echo "<td>";
      Html::autocompletionTextField($this, "label");
      echo "</td>\n";
      echo "</tr>\n";

      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td>".__('Parent')."</td>\n";
      echo "<td>";

      PluginOpenmedisMedicalAccessoryCategory::dropdown(['value' => $this->fields["plugin_openmedis_medicalaccessorycategories_id"],
      'permit_select_parent' => true,
      'displaywith' => ['code','label'],
      'entity' => $this->getEntityID(),
      'used'   => ($ID>0 ? getSonsOf($this->getTable(), $ID)
      : [])]);
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Picture')."</td>\n";
      echo "<td>";

      if (!empty($this->fields['picture'])) {
         echo Html::image(Toolbox::getPictureUrl($this->fields['picture']), [
            'style' => 'max-width: 300px; max-height: 150px;',
            'class' => 'picture_square'
         ]);
         echo "&nbsp;";
         echo Html::getCheckbox([
            'title' => __('Clear'),
            'name'  => "_blank_picture"
         ]);
         echo "&nbsp;".__('Clear');

      } else {
         echo Html::file([
            'name'       => 'picture',
            'onlyimages' => true,
         ]);
      }
      echo "</td></tr>\n";

      if (isset($this->fields['is_protected']) && $this->fields['is_protected']) {
         $options['candel'] = false;
      }

      if (isset($_REQUEST['_in_modal'])) {
         echo "<input type='hidden' name='_in_modal' value='1'>";
      }
      $this->showFormButtons($options);

      return true;
   }

}
