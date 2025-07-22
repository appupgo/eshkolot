<?php
/**
 * Plugin Name: Eshkolot Offline For organization
 * Plugin URI: https://server.eshkolot.net
 * Description: Organization eshkolot offline.
 * Version: 1.0
 * Author: Gitty Gimi
 * Author URI: ''
 */

// add_filter( 'redirect_canonical', '__return_false' );

defined('ABSPATH') or die('No script kiddies please!');
$groups_obj = array();
$group_list = array();
add_shortcode('organization', 'organization');
function organization($atts)
{
  global $groups_obj, $group_list;
  $user_id = get_current_user_id();
  update_user_meta($user_id, 'user_type', 'organization');
  $user_info = get_userdata($user_id);
  $html = 
    '<script src="/wp-content/plugins/eshkolot_offline_for_institution/script.js?date=' . rand() . '"></script>
    <link rel="stylesheet" type="text/css" href="/wp-content/plugins/eshkolot_offline_for_institution/style.css?num=' . rand() . '"/>
    <link rel="stylesheet" type="text/css" href="/wp-content/plugins/eshkolot_offline/offline_css.css?num=' . rand() . '"/>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/rr-1.3.3/sl-1.6.2/datatables.min.css" rel="stylesheet"/>
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/rr-1.3.3/sl-1.6.2/datatables.min.js"></script>
    <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>

    <script>
      var user_name = "' . $user_info->first_name . '";
      var user_email = "' . $user_info->user_email . '";
      var last_name = "' . $user_info->last_name . '";
      var user_id = "' . $user_id . '";
    </script>';

  $html .= organization_1();
  $html .= organization_2();
  $html .= organization_3();
  $html .= organization_4($user_info);
  $html .= organization_5();
  $html .= organization_6();
  
  return
  '<script>
    var groupsObj = ' . json_encode($groups_obj) . ';
    var groupList = ' . json_encode($group_list) . ';
    var frameIndex = ' . get_frame_index() . ';
  </script>' . $html;
}

function get_frame_index()
{
  $index = $_GET['index'];
  return $index ? 2 : 1;
  // return $index ? number_format($index) : 1; //delete???
}

function organization_1()
{
  if (get_frame_index() != 1) {
    $is_display = 'display:none;';
  }
  else {
    $is_display = 'display:block;';
  }
  $res = '<div id="organization_1" style="' . $is_display . '">
  <div class="close-cart">
    <svg class="shopping-back" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
      <path id="Vector" d="M7.09689 5.48114L1.87969 0.273146C1.794 0.18675 1.69205 0.118176 1.57972 0.0713791C1.4674 0.0245821 1.34692 0.000488464 1.22523 0.000488443C1.10355 0.000488421 0.983068 0.024582 0.870742 0.0713789C0.758416 0.118176 0.656468 0.18675 0.570778 0.273146C0.399097 0.44585 0.302734 0.679474 0.302734 0.922992C0.302734 1.16651 0.399097 1.40013 0.570777 1.57284L5.13353 6.18168L0.570776 10.7444C0.399096 10.9171 0.302732 11.1508 0.302732 11.3943C0.302732 11.6378 0.399095 11.8714 0.570775 12.0441C0.656145 12.1312 0.757949 12.2005 0.87029 12.248C0.982631 12.2954 1.10327 12.3201 1.22523 12.3207C1.34719 12.3201 1.46783 12.2954 1.58017 12.248C1.69251 12.2005 1.79432 12.1312 1.87969 12.0441L7.09689 6.83614C7.19046 6.74982 7.26513 6.64506 7.3162 6.52845C7.36727 6.41185 7.39364 6.28593 7.39364 6.15863C7.39364 6.03134 7.36727 5.90542 7.3162 5.78882C7.26513 5.67221 7.19046 5.56745 7.09689 5.48114Z" fill="#6E7072"/>
    </svg>
  </div>
  <p class="header">ערוך את פרטי הארגון</p>
  <div class="wrapper">
    <form class="logo-upload">
      <div class="'. get_logo_class() .'">
        <input id="logoUpload" type="file" name="logoUpload" accept="image/*, svg" style="display: none;">
          <label for="logoUpload" class="logoImg" style="background-image: url('. get_logo_image()[0] .')">
            <img src="/wp-content/uploads/2023/06/offlineLogoImg.svg"/>
          </label>
        <label for="logoUpload">העלה לוגו ארגון</label>
      </div>
      <div class="pencil-icon" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
          <path d="M0.188631 11.973L0.190865 11.9753C0.25011 12.0349 0.320567 12.0823 0.398186 12.1147C0.475805 12.147 0.559055 12.1637 0.643148 12.1638C0.713908 12.1638 0.784179 12.1521 0.851159 12.1293L4.49793 10.8922L11.4958 3.89429C11.9236 3.46645 12.164 2.88619 12.1639 2.28116C12.1639 1.67613 11.9235 1.0959 11.4957 0.668095C11.0679 0.240295 10.4876 -2.64859e-05 9.88257 2.18939e-09C9.27755 2.64902e-05 8.69731 0.240398 8.26951 0.668236L1.2716 7.66615L0.0346253 11.3128C-0.00433934 11.4262 -0.0105588 11.5483 0.01668 11.6651C0.0439189 11.7818 0.103513 11.8886 0.188631 11.973ZM8.85063 1.24931C9.12469 0.977333 9.49536 0.825043 9.88147 0.825785C10.2676 0.826526 10.6377 0.980238 10.9107 1.25326C11.1837 1.52629 11.3374 1.89637 11.3381 2.28248C11.3389 2.66859 11.1866 3.03926 10.9146 3.31332L9.99436 4.23355L7.93035 2.16954L8.85063 1.24931ZM1.98834 8.11157L7.34928 2.75061L9.41329 4.81462L4.05232 10.1756L0.928714 11.2352L1.98834 8.11157Z" fill="black"/>
        </svg>
      </div>
    </form>
    <div class="organization-details">
      <div class="wrapper-details">
        <div class="organization-name">
          <div>שם הארגון</div>
          <input type="text" requierd>
        </div>
        <div class="organization-symbol">
          <div>סמל מוסד</div>
          <input type="text" requierd>
        </div>
      </div>
      <div class="city">
        <div>עיר</div>
        <input id="city_select" list="cities">
        <datalist id="cities">
          <option disabled selected></option>
          ' . israel_city_list() . '
        </datalist>
      </div>
      <div class="agree">
        <input type="checkbox">
        <div>קראתי ואני מסכים&nbsp;<a target="_blank" href="/%d7%aa%d7%a0%d7%90%d7%99-%d7%a9%d7%99%d7%9e%d7%95%d7%a9/">לתנאי השימוש</a>&nbsp;<a target="_blank" href="/privacy-policy/">ולתקנון החברה</a></div>
      </div>
    </div>
    <button class="next" data-event="groups" disabled>ליצירת קבוצות למידה ←</button>
  </div>
  </div>';
  return $res;
}
function organization_2()
{
  if (get_frame_index() != 2) {
    $is_display = 'display:none;';
  }
  else {
    $is_display = 'display:block;';
  }
  $res = '<div id="organization_2" style="' . $is_display . '">
    <div class="close-cart">
      <svg class="shopping-back" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
        <path id="Vector" d="M7.09689 5.48114L1.87969 0.273146C1.794 0.18675 1.69205 0.118176 1.57972 0.0713791C1.4674 0.0245821 1.34692 0.000488464 1.22523 0.000488443C1.10355 0.000488421 0.983068 0.024582 0.870742 0.0713789C0.758416 0.118176 0.656468 0.18675 0.570778 0.273146C0.399097 0.44585 0.302734 0.679474 0.302734 0.922992C0.302734 1.16651 0.399097 1.40013 0.570777 1.57284L5.13353 6.18168L0.570776 10.7444C0.399096 10.9171 0.302732 11.1508 0.302732 11.3943C0.302732 11.6378 0.399095 11.8714 0.570775 12.0441C0.656145 12.1312 0.757949 12.2005 0.87029 12.248C0.982631 12.2954 1.10327 12.3201 1.22523 12.3207C1.34719 12.3201 1.46783 12.2954 1.58017 12.248C1.69251 12.2005 1.79432 12.1312 1.87969 12.0441L7.09689 6.83614C7.19046 6.74982 7.26513 6.64506 7.3162 6.52845C7.36727 6.41185 7.39364 6.28593 7.39364 6.15863C7.39364 6.03134 7.36727 5.90542 7.3162 5.78882C7.26513 5.67221 7.19046 5.56745 7.09689 5.48114Z" fill="#6E7072"/>
      </svg>
    </div>
    <p class="header">קבוצות למידה ארגון&nbsp;<span></span></p> 
    <div class="wrapper">
      <form class="logo-upload2">
        <div class="'. get_logo_class() .'">
          <input id="logoUpload2" name="logoUpload2" type="file" accept="image/*, svg" style="display: none;">
          <p class="logoTitle1">LOGO</p>
          <p class="logoTitle2">לוגו ארגון</p>
          <label for="logoUpload2" class="logoImg" style="background-image: url('. get_logo_image()[0] .')">
            <img src="/wp-content/uploads/2023/06/icon_pencil.svg"/>
          </label>
        </div>
        <div class="pencil-icon" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
            <path d="M0.188631 11.973L0.190865 11.9753C0.25011 12.0349 0.320567 12.0823 0.398186 12.1147C0.475805 12.147 0.559055 12.1637 0.643148 12.1638C0.713908 12.1638 0.784179 12.1521 0.851159 12.1293L4.49793 10.8922L11.4958 3.89429C11.9236 3.46645 12.164 2.88619 12.1639 2.28116C12.1639 1.67613 11.9235 1.0959 11.4957 0.668095C11.0679 0.240295 10.4876 -2.64859e-05 9.88257 2.18939e-09C9.27755 2.64902e-05 8.69731 0.240398 8.26951 0.668236L1.2716 7.66615L0.0346253 11.3128C-0.00433934 11.4262 -0.0105588 11.5483 0.01668 11.6651C0.0439189 11.7818 0.103513 11.8886 0.188631 11.973ZM8.85063 1.24931C9.12469 0.977333 9.49536 0.825043 9.88147 0.825785C10.2676 0.826526 10.6377 0.980238 10.9107 1.25326C11.1837 1.52629 11.3374 1.89637 11.3381 2.28248C11.3389 2.66859 11.1866 3.03926 10.9146 3.31332L9.99436 4.23355L7.93035 2.16954L8.85063 1.24931ZM1.98834 8.11157L7.34928 2.75061L9.41329 4.81462L4.05232 10.1756L0.928714 11.2352L1.98834 8.11157Z" fill="black"/>
          </svg>
        </div>
      </form>
      <p class="header2">צור או ערוך את קבוצות התלמידים</p>
      <div class="wrapNewGroup">
      ' . exist_groups() . '
        <div id="newGroup" class="next" data-event="group-details">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
            <circle cx="12.5" cy="12.5" r="12" stroke="#2D2828"/>
            <line x1="12.5" y1="6.99902" x2="12.5" y2="19.2212" stroke="black"/>
            <line x1="6" y1="12.499" x2="18.2222" y2="12.499" stroke="black"/>
          </svg>
          <div>צור קבוצה</div>
        </div>
      </div>
    </div>
    <div id="bottom-page-btn">
      <div class="prev" data-event="organization-details">לעריכת פרטי המוסד ←</div>
      <div class="proceed-to-payment next" data-event="shopping-cart" style="display: none;">למעבר לתשלום עבור קורסים הממתינים לתשלום ←</div>
    </div>
  </div>';
  return $res;
}
function organization_3()
{
  $res = '<div class="background-opacity">
  <div id="organization_3" style="display: none;">
          <div class="closeBtn" data-event="close">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
              <line x1="23.6932" y1="8.13698" x2="8.12686" y2="23.6834" stroke="black"/>
              <line x1="8.13894" y1="7.42011" x2="23.6853" y2="22.9864" stroke="black"/>
            </svg>
          </div>
        ' . title_icons() . '
            <p class="header">עריכת פרטי קבוצה</p>
            <div class="editOrganization3">
              <label>שם הקבוצה</label>
              <input id="group-name" type="text" placeholder="שם הקבוצה"  maxlength="10" />   
              <label>מגדר</label>
              <div id="gender">
                <div>
                  <input type="radio" id="boys" value="boys" name="gender">
                  <label for="boys">בנים</label>
                </div>
                <div>
                  <input type="radio" id="girls" value="girls" name="gender">
                  <label for="girls">בנות</label>
                </div>
                <div>
                  <input type="radio" id="no_division" value="no_division" name="gender">
                  <label for="no_division">ללא חלוקה</label>
                </div>
                </div>
                <label>טווח גילאים</label>
                <div class="ages">
                <div>
                  <input type="radio" id="10-16" value="10-16" name="age">
                  <label for="10-16">10-16</label>
                </div>
                <div>
                  <input type="radio" id="16-25" value="16-25" name="age">
                  <label for="16-25">16-25</label>
                </div>
                <div>
                  <input type="radio" id="25-35" value="25-35" name="age">
                  <label for="25-35">25-35</label>
                </div>
                <div>
                  <input type="radio" id="35-45" value="35-45" name="age">
                  <label for="35-45">35-45</label>
                </div>
                <div>
                  <input type="radio" id="45+" value="45+" name="age">
                  <label for="45+">45+</label>
                </div>
                </div>
              </div>
              <div class="forward-back-btn">
                <button class="next" data-event="students">המשך ←</button>
              </div>
          </div>
        </div>';
  return $res;
}
function organization_4($user_info)
{
  $res = '<div class="background-opacity">
        <div id="organization_4" style="display: none;">
          <div class="closeBtn" data-event="close" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
              <line x1="23.6932" y1="8.13698" x2="8.12686" y2="23.6834" stroke="black"/>
              <line x1="8.13894" y1="7.42011" x2="23.6853" y2="22.9864" stroke="black"/>
            </svg>
          </div>
          ' . title_icons() . '
            <p class="header">פרטי התלמידים בקבוצה:&nbsp;<span></span></p>
            <p class="main-account">חשבון ראשי</p>
            <div id="main_account">
              <div class="main_account_div">
                <div class="borderLeft">
                  <span>שם פרטי:</span>
                  <span>' . $user_info->first_name . '</span>
                </div>
              </div>
              <div class="main_account_div">
                <div class="borderLeft">
                  <span>שם משפחה:</span>
                  <span>' . $user_info->last_name . '</span>
                </div>
              </div>
              <div class="main_account_div">
                  <span>אימייל:</span>
                  <span>' . $user_info->user_email . '</span>
              </div>
            </div>
            
          <p class="child-account">חשבונות תלמידים</p>
          <div id="excel">
            <a id="excelTemplate" href="/wp-content/uploads/2023/06/תלמידים - אשכולות.xlsx" download>הורד קובץ אקסל לדוגמא</a>
            <label for="excelUpload" class="exceLbl">הוסף מקובץ אקסל
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="20" viewBox="0 0 21 20" fill="none">
              <rect x="0.000549316" width="21" height="20" fill="url(#pattern0_3585_20940)"/>
              <defs>
              <pattern id="pattern0_3585_20940" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_3585_20940" transform="matrix(0.00195312 0 0 0.00205078 0 -0.025)"/>
              </pattern>
              <image id="image0_3585_20940" width="512" height="512" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"/>
              </defs>
            </svg>
            <input id="excelUpload" style="display:none;" type="file" accept=".xlsx, .xls, application/vnd.ms-excel.sheet.macroEnabled.12"/>
            </label>
          </div>
          <div class="container-child-table">
            <div class="add_child add_child2" id="top-btn">
              <label>הוסף תלמיד</label>
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <circle cx="12.5" cy="12.5" r="12" stroke="#2D2828"/>
                <line x1="12.498" y1="6.99902" x2="12.498" y2="19.2212" stroke="black"/>
                <line x1="5.99609" y1="12.499" x2="18.2183" y2="12.499" stroke="black"/>
              </svg>
            </div>
            <div id="wrap_table">
              <table id="child_table"></table>
            </div>
            <div class="add_child add_child2" id="bottom-btn">
              <label>הוסף תלמיד</label>
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <circle cx="12.5" cy="12.5" r="12" stroke="#2D2828"/>
                <line x1="12.498" y1="6.99902" x2="12.498" y2="19.2212" stroke="black"/>
                <line x1="5.99609" y1="12.499" x2="18.2183" y2="12.499" stroke="black"/>
              </svg>
            </div>
          </div>
          <div class="forward-back-btn">
            <button class="next" data-event="courses">המשך ←</button>
          </div>
        </div>
      </div>';
  return $res;
}

function organization_5()
{
  [$course_metadata, $learning_paths] = course_metadata();
  $knowledge_space_lst = '';
  $index = 0;
  $display_index = 1;
  $knowledge_space_lst .= '<dt class="dt-course-header">' . polygon() . '<span>קורסים</span></dt>';
  foreach ($course_metadata as $key => $value) {
    if (!empty($value)) {
      usort($value, function($a, $b) {
        return $a[3] <=> $b[3];
      });
      $style = ($display_index <= 6) ? 'display: block' : 'display: none';
      $knowledge_space_lst .= '<dl>
        <dt style="' . $style . '" data-id="course-id-' . $index . '" class="dt-course-sub-header">' . polygon() . '<span>' . $key . '</span></dt><dl>';
      $display_index++;
      for ($i = 0; $i < count($value); $i++) {
        $name = get_the_title($value[$i][0]);
        $icon_num = get_course_knowledge_num($value[$i][0], $value[$i][2]);
        $new_phrase_icon = str_replace(array("<", ">", '"'), array("&lt;", "&gt;", "&quot;"), $icon_num);
        $style = ($display_index <= 6) ? 'display: block' : 'display: none';
        $knowledge_space_lst .= '<dt class="dt-course" style="' . $style . '"><div data-id="' . $value[$i][0] . '" data-name="' . $name . '" data-price="' . $value[$i][1] . '" data-icon="' . $new_phrase_icon . '">' . cbx_style($value[$i][0]);
        $knowledge_space_lst .= '<label class="course-name" for="cbx-' . $value[$i][0] . '">' . $name . '</label>';
        $knowledge_space_lst .= $icon_num . '</div></dt>';
        $display_index++;
      }
      $index++;
      $knowledge_space_lst .= '</dl></dl>';
    }
  }
  $knowledge_space_lst .= '<dt class="dt-course-header" style="display: none">' . polygon() . '<span>מסלולים</span></dt>';
  $knowledge_space_lst .= '<dl>';
  foreach ($learning_paths as $value) {
    $display_index++;
    $id = $value[0];
    $name = get_the_title($id);
    $price = $value[1];
    $style = ($display_index <= 6) ? 'display: block' : 'display: none';
    $knowledge_space_lst .= '<dt class="dt-course" style="' . $style . '"><div data-id="' . $id . '" data-name="' . $name . '" data-price="' . $price . '">' . cbx_style($id);
    $knowledge_space_lst .= '<label class="course-name" for="cbx-' . $id . '">' . $name . '</label></div></dt>';
    $index++;
  }
  $knowledge_space_lst .= '</dl>';
  $res = '<div class="background-opacity">
            <div id="organization_5" style="display: none;">
            <div class="closeBtn" data-event="close" style="display:none;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <line x1="23.6932" y1="8.13698" x2="8.12686" y2="23.6834" stroke="black"/>
                <line x1="8.13894" y1="7.42011" x2="23.6853" y2="22.9864" stroke="black"/>
              </svg>
            </div>
            ' . title_icons() . '
            <p class="header">רכישת קורסים ומסלולים לקבוצה:&nbsp;<span></span></p>
            <div class="courses_list_wrap">
              <div class="course_header">
                <div class="active" id="all">לכל התלמידים</div>
                <div id="adapted">בהתאמה לכל תלמיד</div>
              </div>
              <div class="child_lst">
                <div id="right-button" style="visibility: hidden;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                    <path d="M7.68831 5.28358L1.76624 0.268656C1.35065 -0.0895525 0.727274 -0.0895526 0.311689 0.268656C-0.103895 0.626865 -0.103895 1.16418 0.311689 1.52239L5.4026 6L0.311689 10.4776C-0.103896 10.8358 -0.103896 11.3731 0.311688 11.7313C0.519481 11.9104 0.727273 12 1.03896 12C1.35065 12 1.55844 11.9104 1.76623 11.7313L7.68831 6.71642C8.1039 6.26866 8.1039 5.73134 7.68831 5.28358C7.68831 5.37313 7.68831 5.37313 7.68831 5.28358Z" fill="#2D2828"/>
                  </svg>
                </div>
                  <div id="outer">
                  </div>
                  <div id="left-button" style="visibility: hidden;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                      <path d="M0.311689 6.71642L6.23377 11.7313C6.64935 12.0896 7.27273 12.0896 7.68831 11.7313C8.1039 11.3731 8.1039 10.8358 7.68831 10.4776L2.5974 6L7.68831 1.52239C8.1039 1.16418 8.1039 0.626865 7.68831 0.268656C7.48052 0.0895515 7.27273 -4.30826e-07 6.96104 -4.12362e-07C6.64935 -3.93898e-07 6.44156 0.0895516 6.23377 0.268656L0.311689 5.28358C-0.103896 5.73134 -0.103896 6.26866 0.311689 6.71642C0.311689 6.62687 0.311689 6.62687 0.311689 6.71642Z" fill="#2D2828"/>
                    </svg>
                  </div>
                </div>
              <div class="courses_list">
                ' . $knowledge_space_lst . '
              </div>
              <div id="dsply-more">הצג עוד</div>
            </div>
            <div style="display: none;" id="lbl_added">
              <svg xmlns="http://www.w3.org/2000/svg" width="11" height="9" viewBox="0 0 11 9" fill="none">
                <path d="M3.90737 8.02924C3.81596 8.02894 3.72559 8.00985 3.64187 7.97315C3.55815 7.93645 3.48287 7.88293 3.42071 7.8159L0.180708 4.36924C0.0595927 4.24016 -0.00528848 4.06827 0.00033761 3.89136C0.0059637 3.71445 0.0816363 3.54702 0.210708 3.4259C0.33978 3.30479 0.511679 3.23991 0.688588 3.24553C0.865497 3.25116 1.03293 3.32683 1.15404 3.4559L3.90071 6.38257L9.50738 0.249237C9.56427 0.178385 9.63505 0.119914 9.71537 0.0774127C9.79569 0.0349119 9.88385 0.00927952 9.97443 0.00208996C10.065 -0.00509961 10.1561 0.0063055 10.2421 0.0356046C10.3281 0.0649037 10.4073 0.111478 10.4746 0.172468C10.542 0.233457 10.5962 0.307574 10.6338 0.390266C10.6715 0.472957 10.6919 0.562478 10.6937 0.65333C10.6955 0.744181 10.6788 0.834445 10.6444 0.918578C10.6101 1.00271 10.5589 1.07894 10.494 1.14257L4.40071 7.80924C4.33914 7.87747 4.26412 7.93224 4.18037 7.9701C4.09662 8.00796 4.00594 8.02809 3.91404 8.02924H3.90737Z" fill="#32D489"/>
              </svg>
              <label>&nbsp;הקורסים ו/המסלולים שבחרת נוספו לסל בהצלחה! כדי לאפשר את הורדת התוכנה תוכל לשלם כעת, <br/>&nbsp;&nbsp;או לשלם דרך סל הקניות לאחר שתערוך את כל הכיתות.</label>
            </div>
            <div class="forward-back-btn">
              <button class="next" data-event="shopping-cart" disabled>לסל הקניות</button>
              <button class="prev" data-event="groups" disabled>צור או ערוך קבוצות נוספות</button>
            </div>
          </div>
        </div>';
  return $res;
}

function organization_6()
{
  $res = '<div id="organization_6" style="display: none;">
            <div class="close-cart">
              <svg class="shopping-close" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                <path id="Vector" d="M7.4158 6.66034L11.7158 2.37034C11.9041 2.18204 12.0099 1.92665 12.0099 1.66034C12.0099 1.39404 11.9041 1.13865 11.7158 0.950344C11.5275 0.76204 11.2721 0.656252 11.0058 0.656252C10.7395 0.656252 10.4841 0.76204 10.2958 0.950344L6.0058 5.25034L1.7158 0.950342C1.52749 0.762038 1.2721 0.65625 1.0058 0.65625C0.739497 0.65625 0.484102 0.762038 0.295798 0.950342C0.107495 1.13865 0.00170647 1.39404 0.00170642 1.66034C0.00170638 1.92664 0.107494 2.18204 0.295798 2.37034L4.5958 6.66034L0.295797 10.9503C0.202068 11.0433 0.127674 11.1539 0.0769053 11.2758C0.0261366 11.3976 -1.90093e-06 11.5283 -1.92402e-06 11.6603C-1.9471e-06 11.7924 0.0261365 11.9231 0.0769052 12.0449C0.127674 12.1668 0.202068 12.2774 0.295796 12.3703C0.388759 12.4641 0.49936 12.5385 0.621219 12.5892C0.743079 12.64 0.873784 12.6661 1.0058 12.6661C1.13781 12.6661 1.26851 12.64 1.39037 12.5892C1.51223 12.5385 1.62283 12.4641 1.7158 12.3703L6.0058 8.07034L10.2958 12.3703C10.3888 12.4641 10.4994 12.5385 10.6212 12.5892C10.7431 12.64 10.8738 12.6661 11.0058 12.6661C11.1378 12.6661 11.2685 12.64 11.3904 12.5892C11.5122 12.5385 11.6228 12.4641 11.7158 12.3703C11.8095 12.2774 11.8839 12.1668 11.9347 12.0449C11.9855 11.9231 12.0116 11.7924 12.0116 11.6603C12.0116 11.5283 11.9855 11.3976 11.9347 11.2758C11.8839 11.1539 11.8095 11.0433 11.7158 10.9503L7.4158 6.66034Z" fill="#ACAEAF"/>
              </svg>
              <svg class="shopping-back" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
                <path id="Vector" d="M7.09689 5.48114L1.87969 0.273146C1.794 0.18675 1.69205 0.118176 1.57972 0.0713791C1.4674 0.0245821 1.34692 0.000488464 1.22523 0.000488443C1.10355 0.000488421 0.983068 0.024582 0.870742 0.0713789C0.758416 0.118176 0.656468 0.18675 0.570778 0.273146C0.399097 0.44585 0.302734 0.679474 0.302734 0.922992C0.302734 1.16651 0.399097 1.40013 0.570777 1.57284L5.13353 6.18168L0.570776 10.7444C0.399096 10.9171 0.302732 11.1508 0.302732 11.3943C0.302732 11.6378 0.399095 11.8714 0.570775 12.0441C0.656145 12.1312 0.757949 12.2005 0.87029 12.248C0.982631 12.2954 1.10327 12.3201 1.22523 12.3207C1.34719 12.3201 1.46783 12.2954 1.58017 12.248C1.69251 12.2005 1.79432 12.1312 1.87969 12.0441L7.09689 6.83614C7.19046 6.74982 7.26513 6.64506 7.3162 6.52845C7.36727 6.41185 7.39364 6.28593 7.39364 6.15863C7.39364 6.03134 7.36727 5.90542 7.3162 5.78882C7.26513 5.67221 7.19046 5.56745 7.09689 5.48114Z" fill="#6E7072"/>
              </svg>
            </div>
            <p class="header">סל הקניות</p>
            <div class="wrap-content">
              <img class="shopping-img" src="/wp-content/uploads/2023/06/shopping-png.png"/>
              <div class="wrap-shpping-details">
                <div class="ttl">סה"כ</div>
                <div class="table-data">
                  <table id="table_course">
                    <thead>
                      <tr>
                        <th>קורסים או מסלולים</th>
                        <th>כמות</th>
                        <th>עבור</th>
                        <th>פיקדון</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="deposit">סה"כ דמי פיקדון: <label id="total-price"></label></div>
                <div id="return-payment-msg">לאחר השלמת הקורס וסינכרון באתר התשלום יושב בשלמות. </br>מוגבל עד שנה</div>
                <div class="forward-back-btn">
                  <div class="pay-and-downloads"><a href="/רכישת-קורסים-לאשכולות-אופליין">לתשלום והורדת התוכנה ←</a></div>
                </div>
              </div>
            </div>
          </div>';
  return $res;
}

function title_icons()
{
  $res = '<div id="title_icons">
            ' . menu_icons() . '
            </div>
            <div id="edit_dtl">
            <span>עריכת פרטי קבוצה</span>
              <span>עריכת פרטי תלמידים</span>
              <span>קורסים ומסלולים</span>
            </div>';
  return $res;
}

function exist_groups()
{
  global $group_list;
  $html = '';
  $user_id = get_current_user_id();
  $groups = get_user_meta($user_id, 'groups', true);
  $group_id = 0;
  foreach ($groups as $key => $value) {
    $general_details = (object) $groups[$key]['general_details'];
    $group_name = $general_details->groupName;
    $gender = $general_details->gender;
    $gender_text = get_gender_text($gender);
    $ages = $general_details->ages;
    $students = (object) $groups[$key]['students'];
    $group_count = count(get_object_vars($students));
    $paid_courses = $groups[$key]['paidCourses'];
    $html .= create_group($group_id, $group_name, $group_count, $gender_text);
    set_groups_obj($group_id, $students, $paid_courses, $group_name, $gender, $ages);
    array_push($group_list, $group_id);
    $group_id++;
  }
  return $html;
}

function create_group($group_id, $group_name, $group_count, $gender)
{
  $res = '<div class="existGroup" data-groupid=' . $group_id . '>
  <div class="active">
    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none">
      <circle cx="3" cy="3" r="3" fill="#12AB64"/>
    </svg>
    <span class="payment">פעיל</span>
  </div>
  <div class="existGroupEdit">
    <svg xmlns="http://www.w3.org/2000/svg" width="5" height="20" viewBox="0 0 5 20" fill="none">
      <circle cx="2.125" cy="2" r="2" fill="#2D2828"/>
      <circle cx="2.125" cy="10" r="2" fill="#2D2828"/>
      <circle cx="2.125" cy="18" r="2" fill="#2D2828"/>
    </svg>
  </div>
  <a href="/personal-area?group_name=' . $group_name . '"></a>
  <p class="existGroupName">' . $group_name . '</p>
  <p class="existGroupCount">' . $group_count .' '. $gender. '</p>
  <div class="popupEditDetils" style="display: none;">
    <div class="popupGroupDetails">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
        <path d="M0.188631 11.973L0.190865 11.9753C0.25011 12.0349 0.320567 12.0823 0.398186 12.1147C0.475805 12.147 0.559055 12.1637 0.643148 12.1638C0.713908 12.1638 0.784179 12.1521 0.851159 12.1293L4.49793 10.8922L11.4958 3.89429C11.9236 3.46645 12.164 2.88619 12.1639 2.28116C12.1639 1.67613 11.9235 1.0959 11.4957 0.668095C11.0679 0.240295 10.4876 -2.64859e-05 9.88257 2.18939e-09C9.27755 2.64902e-05 8.69731 0.240398 8.26951 0.668236L1.2716 7.66615L0.0346253 11.3128C-0.00433934 11.4262 -0.0105588 11.5483 0.01668 11.6651C0.0439189 11.7818 0.103513 11.8886 0.188631 11.973ZM8.85063 1.24931C9.12469 0.977333 9.49536 0.825043 9.88147 0.825785C10.2676 0.826526 10.6377 0.980238 10.9107 1.25326C11.1837 1.52629 11.3374 1.89637 11.3381 2.28248C11.3389 2.66859 11.1866 3.03926 10.9146 3.31332L9.99436 4.23355L7.93035 2.16954L8.85063 1.24931ZM1.98834 8.11157L7.34928 2.75061L9.41329 4.81462L4.05232 10.1756L0.928714 11.2352L1.98834 8.11157Z" fill="black"/>
      </svg>
      עריכת פרטי קבוצה
    </div>
    <div class="popupEditStudent">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14" fill="none">
        <path d="M12.0916 8.27746L10.3493 7.13748L11.0201 5.90765C11.1819 5.60057 11.2667 5.25877 11.2671 4.91167V2.96206C11.2685 2.41386 11.1097 1.87721 10.8103 1.418C10.5109 0.958785 10.0839 0.596999 9.58178 0.377067C9.07965 0.157135 8.52421 0.0886308 7.98368 0.179967C7.44315 0.271304 6.94106 0.518504 6.53906 0.891214L7.12795 1.52692C7.4065 1.26938 7.75415 1.09871 8.12827 1.03584C8.5024 0.972969 8.88673 1.02063 9.23416 1.17299C9.58159 1.32535 9.87702 1.57577 10.0842 1.89356C10.2914 2.21135 10.4014 2.58269 10.4006 2.96206V4.91167C10.4005 5.11378 10.3521 5.31295 10.2594 5.49255L9.20565 7.4245L11.6172 9.00245C11.7763 9.10662 11.907 9.24893 11.9972 9.41644C12.0874 9.58394 12.1343 9.77133 12.1336 9.96157V11.4104H10.184V12.2769H13.0001V9.96155C13.001 9.62737 12.9183 9.29827 12.7597 9.00416C12.601 8.71004 12.3713 8.46024 12.0916 8.27746Z" fill="black"/>
        <path d="M8.19219 9.14393L6.44992 8.00395L7.12072 6.77412C7.28252 6.46704 7.36728 6.12524 7.36772 5.77814V3.82853C7.36717 3.08124 7.07021 2.36468 6.542 1.83606C6.01379 1.30744 5.29747 1.00993 4.55018 1.00879C2.99815 1.00879 1.73551 2.27374 1.73551 3.82853V5.77814C1.73385 6.12508 1.81769 6.46709 1.97962 6.77393L2.65407 8.01042L0.921923 9.14393C0.642482 9.32643 0.413035 9.57581 0.254404 9.86946C0.0957732 10.1631 0.0129804 10.4917 0.013539 10.8255L0 13.1433H9.10071V10.828C9.10159 10.4938 9.01894 10.1647 8.86027 9.87063C8.7016 9.57651 8.47195 9.32671 8.19219 9.14393ZM8.23422 12.2769H0.871531L0.879925 10.828C0.879271 10.6378 0.926173 10.4504 1.01637 10.2829C1.10656 10.1154 1.23719 9.97306 1.39638 9.86889L3.79771 8.29758L2.74029 6.35902C2.64862 6.17915 2.60121 5.98001 2.602 5.77814V3.82853C2.602 3.31146 2.80741 2.81556 3.17303 2.44994C3.53865 2.08432 4.03455 1.87891 4.55162 1.87891C5.06869 1.87891 5.56458 2.08432 5.9302 2.44994C6.29582 2.81556 6.50123 3.31146 6.50123 3.82853V5.77814C6.50115 5.98025 6.45273 6.17942 6.36002 6.35902L5.30625 8.29097L7.71776 9.86892C7.87695 9.97309 8.00757 10.1154 8.09777 10.2829C8.18796 10.4504 8.23487 10.6378 8.23422 10.828V12.2769Z" fill="black"/>
      </svg>
      עריכת פרטי תלמידים
    </div>
    <div class="popupAddStudent">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14" fill="none">
        <path d="M12.0916 8.27746L10.3493 7.13748L11.0201 5.90765C11.1819 5.60057 11.2667 5.25877 11.2671 4.91167V2.96206C11.2685 2.41386 11.1097 1.87721 10.8103 1.418C10.5109 0.958785 10.0839 0.596999 9.58178 0.377067C9.07965 0.157135 8.52421 0.0886308 7.98368 0.179967C7.44315 0.271304 6.94106 0.518504 6.53906 0.891214L7.12795 1.52692C7.4065 1.26938 7.75415 1.09871 8.12827 1.03584C8.5024 0.972969 8.88673 1.02063 9.23416 1.17299C9.58159 1.32535 9.87702 1.57577 10.0842 1.89356C10.2914 2.21135 10.4014 2.58269 10.4006 2.96206V4.91167C10.4005 5.11378 10.3521 5.31295 10.2594 5.49255L9.20565 7.4245L11.6172 9.00245C11.7763 9.10662 11.907 9.24893 11.9972 9.41644C12.0874 9.58394 12.1343 9.77133 12.1336 9.96157V11.4104H10.184V12.2769H13.0001V9.96155C13.001 9.62737 12.9183 9.29827 12.7597 9.00416C12.601 8.71004 12.3713 8.46024 12.0916 8.27746Z" fill="black"/>
        <path d="M8.19219 9.14393L6.44992 8.00395L7.12072 6.77412C7.28252 6.46704 7.36728 6.12524 7.36772 5.77814V3.82853C7.36717 3.08124 7.07021 2.36468 6.542 1.83606C6.01379 1.30744 5.29747 1.00993 4.55018 1.00879C2.99815 1.00879 1.73551 2.27374 1.73551 3.82853V5.77814C1.73385 6.12508 1.81769 6.46709 1.97962 6.77393L2.65407 8.01042L0.921923 9.14393C0.642482 9.32643 0.413035 9.57581 0.254404 9.86946C0.0957732 10.1631 0.0129804 10.4917 0.013539 10.8255L0 13.1433H9.10071V10.828C9.10159 10.4938 9.01894 10.1647 8.86027 9.87063C8.7016 9.57651 8.47195 9.32671 8.19219 9.14393ZM8.23422 12.2769H0.871531L0.879925 10.828C0.879271 10.6378 0.926173 10.4504 1.01637 10.2829C1.10656 10.1154 1.23719 9.97306 1.39638 9.86889L3.79771 8.29758L2.74029 6.35902C2.64862 6.17915 2.60121 5.98001 2.602 5.77814V3.82853C2.602 3.31146 2.80741 2.81556 3.17303 2.44994C3.53865 2.08432 4.03455 1.87891 4.55162 1.87891C5.06869 1.87891 5.56458 2.08432 5.9302 2.44994C6.29582 2.81556 6.50123 3.31146 6.50123 3.82853V5.77814C6.50115 5.98025 6.45273 6.17942 6.36002 6.35902L5.30625 8.29097L7.71776 9.86892C7.87695 9.97309 8.00757 10.1154 8.09777 10.2829C8.18796 10.4504 8.23487 10.6378 8.23422 10.828V12.2769Z" fill="black"/>
      </svg>
      הוספת תלמידים
    </div>
    <div class="popupAddCourse">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
        <path d="M9.74888 8.84092L6.49888 10.669L3.24888 8.84092V6.90943L2.32031 6.39355V9.38396L6.49888 11.7344L10.6775 9.38396V6.39355L9.74888 6.90943V8.84092Z" fill="black"/>
        <path d="M6.5 0L0 3.37037V4.17515L6.5 7.78616L12.0714 4.69103V7.25513H13V3.37037L6.5 0ZM11.1429 4.14465L10.2143 4.6605L6.5 6.72411L2.78571 4.6605L1.85714 4.14465L1.21356 3.78709L6.5 1.04598L11.7864 3.78709L11.1429 4.14465Z" fill="black"/>
      </svg>
    קורסים ומסלולים
    </div>
  </div>
  </div>';
  return $res;
}

function get_gender_text($gender){
  if ($gender == 'boys') return 'תלמידים';
  if ($gender == 'girls') return 'תלמידות';
  else return 'תלמידים/ות';
}

function set_groups_obj($group_id, $students, $paid_courses, $group_name, $gender, $ages){
  global $groups_obj;
	$groups_obj[$group_id]['payment'] = true;
  $groups_obj[$group_id]['students'] = $students;
  $groups_obj[$group_id]['paidCourses']['all'] = $paid_courses['all'];
  $groups_obj[$group_id]['paidCourses']['adapted'] = $paid_courses['adapted'];
  $groups_obj[$group_id]['groupName'] = $group_name;
  $groups_obj[$group_id]['gender'] = $gender;
  $groups_obj[$group_id]['ages'] = $ages;
  $groups_obj[$group_id]['courses']['all'] = [];
  $groups_obj[$group_id]['courses']['adapted'] = [];
  foreach($students as $id => $val) {
    $groups_obj[$group_id]['courses']['adapted'][$id]['all'] = []; 
    $groups_obj[$group_id]['courses']['adapted'][$id]['private'] = []; 
  }
}

add_action('wp_ajax_save_general_details', 'save_general_details');
add_action('wp_ajax_nporiv_save_general_details', 'save_general_details');
function save_general_details(){
  $user_id = $_POST['userId'];
  $group_id = $_POST['groupId'];
  $group_name = $_POST['groupName'];
  $prev_group_name = $_POST['prevGroupName'];
  $general_details = $_POST['generalDetails'];
  $groups = get_user_meta($user_id, 'groups', true);
  $groups[$group_name] = $groups[$prev_group_name];
  $groups[$group_name]['general_details'] = $general_details;
  if ($prev_group_name != $group_name){
    unset($groups[$prev_group_name]);
  }
  update_user_meta($user_id, 'groups', $groups);
}

add_action('wp_ajax_save_students_details', 'save_students_details');
add_action('wp_ajax_nporiv_save_students_details', 'save_students_details');
function save_students_details(){
  $user_id = $_POST['userId'];
  $group_id = $_POST['groupId'];
  $group_name = $_POST['groupName'];
  $students = $_POST['students'];
  $groups = get_user_meta($user_id, 'groups', true);
  $groups[$group_name]['students'] = $students;
  update_user_meta($user_id, 'groups', $groups);
}

function get_logo_image(){
  $img = "'" .  get_user_meta(get_current_user_id(), 'logo_image', true) . "'";
  $is_exist = true;
  if($img == '') {
    $img = '/wp-content/uploads/2023/12/Group-238305.svg';
    $is_exist = false;
  }
  return [$img, $is_exist];
}

function get_logo_class(){
  if(get_logo_image()[1]) {
    return 'existImg';
  }
  return 'logo';
}

function israel_city_list()
{
  return "<option>סנסנה</option><option>חברון</option><option>קצר א-סר</option><option>כמאנה</option><option>אעצם (שבט)</option><option>אבירים</option><option>אבו עבדון (שבט)</option>
  <option>אבו עמאר (שבט)</option><option>אבו עמרה (שבט)</option><option>אבו גוש</option><option>אבו ג'ווייעד (שבט)</option><option>אבו קורינאת (שבט)</option><option>אבו קרינאת (יישוב)</option>
  <option>אבו רובייעה (שבט)</option><option>אבו רוקייק (שבט)</option><option>אבו סנאן</option><option>אבו סריחאן (שבט)</option><option>אבו תלול</option><option>אדמית</option><option>עדנים</option>
  <option>אדרת</option><option>אדירים</option><option>עדי</option><option>אדורה</option><option>אפיניש (שבט)</option><option>אפק</option><option>אפיק</option><option>אפיקים</option><option>עפולה</option>
  <option>עגור</option><option>אחווה</option><option>אחיעזר</option><option>אחיהוד</option><option>אחיסמך</option><option>אחיטוב</option><option>אחוזם</option><option>אחוזת ברק</option><option>עכו</option>
  <option>אל סייד</option><option>אל-עריאן</option><option>אל-עזי</option><option>עלי זהב</option><option>אלפי מנשה</option><option>אלון הגליל</option><option>אלון שבות</option><option>אלוני אבא</option>
  <option>אלוני הבשן</option><option>אלוני יצחק</option><option>אלונים</option><option>עלמה</option><option>אלמגור</option><option>אלמוג</option><option>עלמון</option><option>עלומים</option><option>אלומה</option>
  <option>אלומות</option><option>אמציה</option><option>עמיר</option><option>אמירים</option><option>עמיעד</option><option>עמיעוז</option><option>עמיחי</option><option>עמינדב</option><option>עמיקם</option>
  <option>אמנון</option><option>עמקה</option><option>עמוקה</option><option>אניעם</option><option>ערערה</option><option>ערערה-בנגב</option><option>ערד</option><option>עראמשה</option><option>ארבל</option>
  <option>ארגמן</option><option>אריאל</option><option>ערב אל נעים</option><option>עראבה</option><option>ארסוף</option><option>ערוגות</option><option>אסד (שבט)</option><option>אספר</option><option>עשרת</option>
  <option>אשלים</option><option>אשדוד</option><option>אשדות יעקב (איחוד)</option><option>אשדות יעקב (מאוחד)</option><option>אשרת</option><option>אשקלון</option><option>עטאוונה (שבט)</option><option>עטרת</option>
  <option>עתלית</option><option>אטרש (שבט)</option><option>עצמון שגב</option><option>עבדון</option><option>אבנת</option><option>אביאל</option><option>אביעזר</option><option>אביגדור</option><option>אביחיל</option>
  <option>אביטל</option><option>אביבים</option><option>אבני איתן</option><option>אבני חפץ</option><option>אבשלום</option><option>אבטליון</option><option>עיינות</option><option>איילת השחר</option><option>עזריה</option>
  <option>אזור</option><option>עזריאל</option><option>עזריקם</option><option>בחן</option><option>בלפוריה</option><option>באקה אל-גרביה</option><option>בר גיורא</option><option>בר יוחאי</option><option>ברעם</option>
  <option>ברק</option><option>ברקת</option><option>ברקן</option><option>ברקאי</option><option>בסמ\"ה</option><option>בסמת טבעון</option><option>בת עין</option><option>בת הדר</option><option>בת חפר</option><option>בת חן</option>
  <option>בת שלמה</option><option>בת ים</option><option>בצרה</option><option>באר מילכה</option><option>באר אורה</option><option>באר שבע</option><option>באר טוביה</option><option>באר יעקב</option><option>בארי</option>
  <option>בארות יצחק</option><option>בארותיים</option><option>באר גנים</option><option>בית ג'ן</option><option>בן עמי</option><option>בן שמן (מושב)</option><option>בן שמן (כפר נוער)</option><option>בן זכאי</option>
  <option>בניה</option><option>בני עטרות</option><option>בני עי\"ש</option><option>בני ברק</option><option>בני דרום</option><option>בני דרור</option><option>בני נצרים</option><option>בני ראם</option><option>בני יהודה</option>
  <option>בני ציון</option><option>בקעות</option><option>בקוע</option><option>ברכה</option><option>ברכיה</option><option>ברור חיל</option><option>ברוש</option><option>בית אלפא</option><option>בית עריף</option>
  <option>בית אריה</option><option>בית ברל</option><option>בית דגן</option><option>בית אל</option><option>בית אלעזרי</option><option>בית עזרא</option><option>בית גמליאל</option><option>בית גוברין</option>
  <option>בית הערבה</option><option>בית העמק</option><option>בית הגדי</option><option>בית הלוי</option><option>בית חנן</option><option>בית חנניה</option><option>בית השיטה</option><option>בית חשמונאי</option>
  <option>בית חירות</option><option>בית הלל</option><option>בית חלקיה</option><option>בית חורון</option><option>בית לחם הגלילית</option><option>בית מאיר</option><option>בית נחמיה</option><option>בית נקופה</option>
  <option>בית ניר</option><option>בית אורן</option><option>בית עובד</option><option>בית קמה</option><option>בית קשת</option><option>בית רבן</option><option>בית רימון</option><option>בית שאן</option><option>בית שערים</option>
  <option>בית שמש</option><option>בית שקמה</option><option>בית עוזיאל</option><option>בית ינאי</option><option>בית יהושע</option><option>בית יצחק-שער חפר</option><option>בית יוסף</option><option>בית זית</option>
  <option>בית זיד</option><option>בית זרע</option><option>בית צבי</option><option>ביתר עילית</option><option>בצת</option><option>בענה</option><option>בנימינה-גבעת עדה</option><option>ביר אל-מכסור</option><option>ביר הדאג'</option>
  <option>ביריה</option><option>ביתן אהרן</option><option>בטחה</option><option>ביצרון</option><option>בני דקלים</option><option>ברוכין</option><option>בועיינה-נוג'ידאת</option><option>בוקעאתא</option><option>בורגתה</option>
  <option>בוסתן הגליל</option><option>דבוריה</option><option>דפנה</option><option>דחי</option><option>דאלית אל-כרמל</option><option>דליה</option><option>דלתון</option><option>דן</option><option>דברת</option>
  <option>דגניה א'</option><option>דגניה ב'</option><option>דייר אל-אסד</option><option>דייר חנא</option><option>דייר ראפאת</option><option>דמיידה</option><option>דקל</option><option>דריג'את</option><option>דבורה</option>
  <option>דימונה</option><option>דישון</option><option>דולב</option><option>דור</option><option>דורות</option><option>דוב\"ב</option><option>דביר</option><option>אפרת</option><option>עיילבון</option><option>עין אל-אסד</option>
  <option>עין חוד</option><option>עין מאהל</option><option>עין נקובא</option><option>עין קנייא</option><option>עין ראפה</option><option>אלעד</option><option>אלעזר</option><option>אל-רום</option><option>אילת</option><option>עלי</option>
  <option>אלי-עד</option><option>אליאב</option><option>אליפז</option><option>אליפלט</option><option>אלישמע</option><option>אילון</option><option>אלון מורה</option><option>אילות</option><option>אלקנה</option><option>אלקוש</option>
  <option>אליכין</option><option>אליקים</option><option>אלישיב</option><option>אמונים</option><option>עין איילה</option><option>עין דור</option><option>עין גדי</option><option>עין גב</option><option>עין הבשור</option><option>עין העמק</option>
  <option>עין החורש</option><option>עין המפרץ</option><option>עין הנצי\"ב</option><option>עין חרוד (איחוד)</option><option>עין חרוד (מאוחד)</option><option>עין השלושה</option><option>עין השופט</option><option>עין חצבה</option><option>עין הוד</option>
  <option>עין עירון</option><option>עין כרם-בי\"ס חקלאי</option><option>עין כרמל</option><option>עין שריד</option><option>עין שמר</option><option>עין תמר</option><option>עין ורד</option><option>עין יעקב</option><option>עין יהב</option>
  <option>עין זיוון</option><option>עין צורים</option><option>עינת</option><option>ענב</option><option>ארז</option><option>אשבול</option><option>אשל הנשיא</option><option>אשחר</option><option>אשכולות</option><option>אשתאול</option>
  <option>איתן</option><option>איתנים</option><option>אתגר</option><option>אבן מנחם</option><option>אבן ספיר</option><option>אבן שמואל</option><option>אבן יהודה</option><option>גלעד (אבן יצחק)</option><option>עברון</option>
  <option>אייל</option><option>עזר</option><option>עזוז</option><option>פסוטה</option><option>פוריידיס</option><option>געש</option><option>געתון</option><option>גדיש</option><option>גדות</option><option>גלאון</option><option>גן הדרום</option>
  <option>גן השומרון</option><option>גן חיים</option><option>גן נר</option><option>גן שלמה</option><option>גן שמואל</option><option>גן שורק</option><option>גן יבנה</option><option>גן יאשיה</option><option>גני עם</option><option>גני הדר</option>
  <option>גני מודיעין</option><option>גני טל</option><option>גני תקווה</option><option>גני יוחנן</option><option>גנות</option><option>גנות הדר</option><option>גת רימון</option><option>גת (קיבוץ)</option><option>גזית</option><option>גיאה</option>
  <option>גאליה</option><option>גאולי תימן</option><option>גאולים</option><option>גדרה</option><option>גפן</option><option>גליל ים</option><option>גרופית</option><option>גשר</option><option>גשר הזיו</option><option>גשור</option><option>גבע</option>
  <option>גבע כרמל</option><option>גבע בנימין</option><option>גבעות בר</option><option>גברעם</option><option>גבת</option><option>גבים</option><option>גבולות</option><option>גזר</option><option>ע'ג'ר</option><option>גיבתון</option><option>גדעונה</option>
  <option>גילת</option><option>גלגל</option><option>גילון</option><option>גמזו</option><option>גינתון</option><option>גיניגר</option><option>גינוסר</option><option>גיתה</option><option>גיתית</option><option>גבעת אבני</option><option>גבעת ברנר</option>
  <option>גבעת אלה</option><option>גבעת השלושה</option><option>גבעת חיים (איחוד)</option><option>גבעת חיים (מאוחד)</option><option>גבעת ח\"ן</option><option>גבעת כ\"ח</option><option>גבעת ניל\"י</option><option>גבעת עוז</option><option>גבעת שפירא</option>
  <option>גבעת שמש</option><option>גבעת שמואל</option><option>גבעת יערים</option><option>גבעת ישעיהו</option><option>גבעת יואב</option><option>גבעת זאב</option><option>גבעתיים</option><option>גבעתי</option><option>גבעולים</option><option>גבעון החדשה</option>
  <option>גבעות עדן</option><option>גיזו</option><option>גונן</option><option>גורן</option><option>גורנות הגליל</option><option>הבונים</option><option>חד-נס</option><option>הדר עם</option><option>חדרה</option><option>חדיד</option><option>חפץ חיים</option>
  <option>חגי</option><option>חגור</option><option>הגושרים</option><option>החותרים</option><option>חיפה</option><option>חלוץ</option><option>המעפיל</option><option>חמדיה</option><option>חמאם</option><option>חמרה</option><option>חניתה</option><option>חנתון</option>
  <option>חניאל</option><option>העוגן</option><option>האון</option><option>הר אדר</option><option>הר עמשא</option><option>הר גילה</option><option>הראל</option><option>הררית</option><option>חרשים</option><option>הרדוף</option><option>חריש</option><option>חרוצים</option>
  <option>חשמונאים</option><option>הסוללים</option><option>חספין</option><option>חבצלת השרון</option><option>הוואשלה (שבט)</option><option>היוגב</option><option>חצב</option><option>חצרים</option><option>חצבה</option><option>חזון</option><option>חצור הגלילית</option>
  <option>חצור-אשדוד</option><option>הזורעים</option><option>הזורע</option><option>חפצי-בה</option><option>חלץ</option><option>חמד</option><option>חרב לאת</option><option>חרמש</option><option>חירות</option><option>הרצליה</option><option>חבר</option><option>חיבת ציון</option>
  <option>הילה</option><option>חיננית</option><option>הוד השרון</option><option>הודיות</option><option>הודיה</option><option>חופית</option><option>חגלה</option><option>חולית</option><option>חולון</option><option>חורשים</option><option>חוסן</option><option>הושעיה</option>
  <option>חוג'ייראת (ד'הרה)</option><option>חולתה</option><option>חולדה</option><option>חוקוק</option><option>חורה</option><option>חורפיש</option><option>חוסנייה</option><option>הוזייל (שבט)</option><option>אעבלין</option><option>איבים</option><option>אבטין</option>
  <option>עידן</option><option>אכסאל</option><option>אילניה</option><option>עילוט</option><option>עמנואל</option><option>עיר אובות</option><option>אירוס</option><option>עספיא</option><option>איתמר</option><option>ג'ת</option><option>ג'לג'וליה</option><option>ירושלים</option>
  <option>ג'ש (גוש חלב)</option><option>ג'סר א-זרקא</option><option>ג'דיידה-מכר</option><option>ג'ולס</option><option>ג'נאביב (שבט)</option><option>כעביה-טבאש-חג'אג'רה</option><option>כברי</option><option>כאבול</option><option>כדיתה</option><option>כדורי</option>
  <option>כפר ברא</option><option>כפר כמא</option><option>כפר כנא</option><option>כפר מנדא</option><option>כפר מצר</option><option>כפר קרע</option><option>כפר קאסם</option><option>כפר יאסיף</option><option>כחל</option><option>כלנית</option><option>כמון</option>
  <option>כנף</option><option>כנות</option><option>כאוכב אבו אל-היג'א</option><option>כרי דשא</option><option>כרכום</option><option>כרמי קטיף</option><option>כרמי יוסף</option><option>כרמי צור</option><option>כרמל</option><option>כרמיאל</option><option>כרמיה</option>
  <option>כפר אדומים</option><option>כפר אחים</option><option>כפר אביב</option><option>כפר עבודה</option><option>כפר עזה</option><option>כפר ברוך</option><option>כפר ביאליק</option><option>כפר ביל\"ו</option><option>כפר בן נון</option><option>כפר בלום</option>
  <option>כפר דניאל</option><option>כפר עציון</option><option>כפר גלים</option><option>כפר גדעון</option><option>כפר גלעדי</option><option>כפר גליקסון</option><option>כפר חב\"ד</option><option>כפר החורש</option><option>כפר המכבי</option><option>כפר הנגיד</option>
  <option>כפר חנניה</option><option>כפר הנשיא</option><option>כפר הנוער הדתי</option><option>כפר האורנים</option><option>כפר הרי\"ף</option><option>כפר הרא\"ה</option><option>כפר חרוב</option><option>כפר חסידים א'</option><option>כפר חסידים ב'</option>
  <option>כפר חיים</option><option>כפר הס</option><option>כפר חיטים</option><option>כפר חושן</option><option>כפר קיש</option><option>כפר מל\"ל</option><option>כפר מסריק</option><option>כפר מימון</option><option>כפר מנחם</option><option>כפר מונש</option>
  <option>כפר מרדכי</option><option>כפר נטר</option><option>כפר פינס</option><option>כפר ראש הנקרה</option><option>כפר רוזנואלד (זרעית)</option><option>כפר רופין</option><option>כפר רות</option><option>כפר סבא</option><option>כפר שמאי</option>
  <option>כפר שמריהו</option><option>כפר שמואל</option><option>כפר סילבר</option><option>כפר סירקין</option><option>כפר סאלד</option><option>כפר תפוח</option><option>כפר תבור</option><option>כפר טרומן</option><option>כפר אוריה</option><option>כפר ויתקין</option>
  <option>כפר ורבורג</option><option>כפר ורדים</option><option>כפר יעבץ</option><option>כפר יחזקאל</option><option>כפר יהושע</option><option>כפר יונה</option><option>כפר זיתים</option><option>כפר זוהרים</option><option>כליל</option><option>כמהין</option>
  <option>כרמים</option><option>כרם בן שמן</option><option>כרם בן זמרה</option><option>כרם ביבנה (ישיבה)</option><option>כרם מהר\"ל</option><option>כרם שלום</option><option>כסלון</option><option>ח'ואלד (שבט)</option><option>ח'ואלד</option><option>כנרת (מושבה)</option>
  <option>כנרת (קבוצה)</option><option>כישור</option><option>כסרא-סמיע</option><option>כיסופים</option><option>כחלה</option><option>כוכב השחר</option><option>כוכב מיכאל</option><option>כוכב יעקב</option><option>כוכב יאיר</option><option>כורזים</option><option>כסיפה</option>
  <option>להב</option><option>להבות הבשן</option><option>להבות חביבה</option><option>לכיש</option><option>לפיד</option><option>לפידות</option><option>לקיה</option><option>לביא</option><option>לבון</option><option>להבים</option><option>שריגים (לי-און)</option>
  <option>לימן</option><option>לבנים</option><option>לוד</option><option>לוחמי הגיטאות</option><option>לוטן</option><option>לוטם</option><option>לוזית</option><option>מעגן</option><option>מעגן מיכאל</option><option>מעלה אדומים</option><option>מעלה עמוס</option>
  <option>מעלה אפרים</option><option>מעלה גמלא</option><option>מעלה גלבוע</option><option>מעלה החמישה</option><option>מעלה עירון</option><option>מעלה לבונה</option><option>מעלה מכמש</option><option>מעלות-תרשיחא</option><option>מענית</option><option>מעש</option>
  <option>מעברות</option><option>מעגלים</option><option>מעון</option><option>מאור</option><option>מעוז חיים</option><option>מעין ברוך</option><option>מעין צבי</option><option>מבועים</option><option>מגן</option><option>מגן שאול</option><option>מגל</option>
  <option>מגשימים</option><option>מחניים</option><option>צוקים</option><option>מחנה הילה</option><option>מחנה מרים</option><option>מחנה טלי</option><option>מחנה תל נוף</option><option>מחנה יפה</option><option>מחנה יתיר</option><option>מחנה יהודית</option>
  <option>מחנה יוכבד</option><option>מחסיה</option><option>מג'ד אל-כרום</option><option>מג'דל שמס</option><option>מכחול</option><option>מלכיה</option><option>מנוף</option><option>מנות</option><option>מנשית זבדה</option><option>מרגליות</option><option>מסעדה</option>
  <option>מסעודין אל-עזאזמה</option><option>משאבי שדה</option><option>משען</option><option>משכיות</option><option>מסלול</option><option>מסד</option><option>מסדה</option><option>משואה</option><option>משואות יצחק</option><option>מטע</option><option>מתן</option>
  <option>מתת</option><option>מתתיהו</option><option>מבקיעים</option><option>מזכרת בתיה</option><option>מצליח</option><option>מזור</option><option>מזרעה</option><option>מצובה</option><option>מי עמי</option><option>מאיר שפיה</option><option>מעונה</option>
  <option>מפלסים</option><option>מגדים</option><option>מגידו</option><option>מחולה</option><option>מייסר</option><option>מכורה</option><option>מלאה</option><option>מלילות</option><option>מנחמיה</option><option>מנרה</option><option>מנוחה</option><option>מירב</option>
  <option>מרחב עם</option><option>מרחביה (מושב)</option><option>מרחביה (קיבוץ)</option><option>מרכז שפירא</option><option>מרום גולן</option><option>מירון</option><option>מישר</option><option>משהד</option><option>מסילת ציון</option><option>מסילות</option>
  <option>מיטל</option><option>מיתר</option><option>מיטב</option><option>מטולה</option><option>מבשרת ציון</option><option>מבוא ביתר</option><option>מבוא דותן</option><option>מבוא חמה</option><option>מבוא חורון</option><option>מבוא מודיעים</option>
  <option>מבואות ים</option><option>מבואות יריחו</option><option>מצדות יהודה</option><option>מיצר</option><option>מצר</option><option>מעיליא</option><option>מדרך עוז</option><option>מדרשת בן גוריון</option><option>מדרשת רופין</option><option>מגדל</option>
  <option>מגדל העמק</option><option>מגדל עוז</option><option>מגדלים</option><option>מכמנים</option><option>מכמורת</option><option>מקווה ישראל</option><option>משגב עם</option><option>משגב דב</option><option>משמר איילון</option><option>משמר דוד</option>
  <option>משמר העמק</option><option>משמר הנגב</option><option>משמר השרון</option><option>משמר השבעה</option><option>משמר הירדן</option><option>משמרות</option><option>משמרת</option><option>מצפה אילן</option><option>מבטחים</option><option>מצפה</option>
  <option>מצפה אבי\"ב</option><option>מצפה נטופה</option><option>מצפה רמון</option><option>מצפה שלם</option><option>מצפה יריחו</option><option>מזרע</option><option>מודיעין עילית</option><option>מודיעין-מכבים-רעות</option><option>מולדת</option>
  <option>מורן</option><option>מורשת</option><option>מוצא עילית</option><option>מגאר</option><option>מוקייבלה</option><option>נעלה</option><option>נען</option><option>נערן</option><option>נאעורה</option><option>נעמ\"ה</option><option>אשבל</option>
  <option>חמדת</option><option>נחל עוז</option><option>שיטים</option><option>נחלה</option><option>נהלל</option><option>נחליאל</option><option>נחם</option><option>נהריה</option><option>נחף</option><option>נחשולים</option><option>נחשון</option>
  <option>נחשונים</option><option>נצאצרה (שבט)</option><option>נטף</option><option>נטור</option><option>נווה</option><option>נצרת</option><option>נאות גולן</option><option>נאות הכיכר</option><option>נאות מרדכי</option><option>נעורים</option><
  option>נגבה</option><option>נגוהות</option><option>נחלים</option><option>נהורה</option><option>נחושה</option><option>ניין</option><option>נס עמים</option><option>נס הרים</option><option>נס ציונה</option><option>נשר</option><option>נטע</option>
  <option>נטעים</option><option>נתניה</option><option>נתיב העשרה</option><option>נתיב הגדוד</option><option>נתיב הל\"ה</option><option>נתיב השיירה</option><option>נתיבות</option><option>נטועה</option><option>נבטים</option><option>נוה צוף</option>
  <option>נווה אטי\"ב</option><option>נווה אבות</option><option>נווה דניאל</option><option>נווה איתן</option><option>נווה חריף</option><option>נווה אילן</option><option>נווה מיכאל</option><option>נווה מבטח</option><option>נווה שלום</option>
  <option>נווה אור</option><option>נווה ים</option><option>נווה ימין</option><option>נווה ירק</option><option>נווה זיו</option><option>נווה זוהר</option><option>נצר חזני</option><option>נצר סרני</option><option>ניל\"י</option><option>נמרוד</option>
  <option>ניר עם</option><option>ניר עקיבא</option><option>ניר בנים</option><option>ניר דוד (תל עמל)</option><option>ניר אליהו</option><option>ניר עציון</option><option>ניר גלים</option><option>ניר ח\"ן</option><option>ניר משה</option><option>ניר עוז</option>
  <option>ניר יפה</option><option>ניר ישראל</option><option>ניר יצחק</option><option>ניר צבי</option><option>נירים</option><option>נירית</option><option>ניצן</option><option>ניצן ב'</option><option>ניצנה (קהילת חינוך)</option><option>ניצני עוז</option>
  <option>ניצני סיני</option><option>ניצנים</option><option>נועם</option><option>נוף איילון</option><option>נוף הגליל</option><option>נופך</option><option>נופים</option><option>נופית</option><option>נוגה</option><option>נוקדים</option><option>נורדיה</option>
  <option>נוב</option><option>נורית</option><option>אודם</option><option>אופקים</option><option>עופר</option><option>עופרה</option><option>אוהד</option><option>עולש</option><option>אומן</option><option>עומר</option><option>אומץ</option><option>אור עקיבא</option>
  <option>אור הגנוז</option><option>אור הנר</option><option>אור יהודה</option><option>אורה</option><option>אורנים</option><option>אורנית</option><option>אורות</option><option>אורטל</option><option>עתניאל</option><option>עוצם</option><option>פעמי תש\"ז</option>
  <option>פלמחים</option><option>פארן</option><option>פרדס חנה-כרכור</option><option>פרדסיה</option><option>פרוד</option><option>פטיש</option><option>פדיה</option><option>פדואל</option><option>פדויים</option><option>פלך</option><option>פני חבר</option>
  <option>פקיעין (בוקייעה)</option><option>פקיעין חדשה</option><option>פרזון</option><option>פרי גן</option><option>פסגות</option><option>פתח תקווה</option><option>פתחיה</option><option>פצאל</option><option>פורת</option><option>פוריה עילית</option>
  <option>פוריה - כפר עבודה</option><option>פוריה - נווה עובד</option><option>קבועה (שבט)</option><option>קדרים</option><option>קדימה-צורן</option><option>קלנסווה</option><option>קליה</option><option>קרני שומרון</option><option>קוואעין (שבט)</option>
  <option>קציר</option><option>קצרין</option><option>קדר</option><option>קדמה</option><option>קדומים</option><option>קלע</option><option>קלחים</option><option>קיסריה</option><option>קשת</option><option>קטורה</option><option>קבוצת יבנה</option>
  <option>קדמת צבי</option><option>קדרון</option><option>קרית ענבים</option><option>קרית ארבע</option><option>קרית אתא</option><option>קרית ביאליק</option><option>קרית עקרון</option><option>קרית גת</option><option>קרית מלאכי</option>
  <option>קרית מוצקין</option><option>קרית נטפים</option><option>קרית אונו</option><option>קרית שלמה</option><option>קרית שמונה</option><option>קרית טבעון</option><option>קרית ים</option><option>קרית יערים</option>
  <option>קרית יערים(מוסד)</option><option>קוממיות</option><option>קורנית</option><option>קודייראת א-צאנע(שבט)</option><option>רעננה</option><option>רהט</option><option>רם-און</option><option>רמת דוד</option><option>רמת גן</option>
  <option>רמת הכובש</option><option>רמת השרון</option><option>רמת השופט</option><option>רמת מגשימים</option><option>רמת רחל</option><option>רמת רזיאל</option><option>רמת ישי</option><option>רמת יוחנן</option><option>רמת צבי</option>
  <option>ראמה</option><option>רמלה</option><option>רמות</option><option>רמות השבים</option><option>רמות מאיר</option><option>רמות מנשה</option><option>רמות נפתלי</option><option>רנן</option><option>רקפת</option><option>ראס אל-עין</option>
  <option>ראס עלי</option><option>רביד</option><option>רעים</option><option>רגבים</option><option>רגבה</option><option>ריחן</option><option>רחלים</option><option>רחוב</option><option>רחובות</option><option>ריחאניה</option><option>ריינה</option>
  <option>רכסים</option><option>רשפים</option><option>רתמים</option><option>רבדים</option><option>רבבה</option><option>רביבים</option><option>רווחה</option><option>רוויה</option><option>רימונים</option><option>רינתיה</option><option>ראשון לציון</option>
  <option>רשפון</option><option>רועי</option><option>ראש העין</option><option>ראש פינה</option><option>ראש צורים</option><option>רותם</option><option>רוח מדבר</option><option>רוחמה</option><option>רומת הייב</option><option>רומאנה</option><option>סעד</option>
  <option>סער</option><option>סעוה</option><option>סאג'ור</option><option>סח'נין</option><option>סלעית</option><option>סלמה</option><option>סמר</option><option>צנדלה</option><option>ספיר</option><option>שריד</option><option>סאסא</option><option>סביון</option>
  <option>סואעד (כמאנה) (שבט)</option><option>סואעד (חמרייה)</option><option>סייד (שבט)</option><option>שדי אברהם</option><option>שדה בוקר</option><option>שדה דוד</option><option>שדה אליעזר</option><option>שדה אליהו</option><option>שדי חמד</option>
  <option>שדה אילן</option><option>שדה משה</option><option>שדה נחום</option><option>שדה נחמיה</option><option>שדה ניצן</option><option>שדי תרומות</option><option>שדה עוזיהו</option><option>שדה ורבורג</option><option>שדה יעקב</option><option>שדה יצחק</option>
  <option>שדה יואב</option><option>שדה צבי</option><option>שדרות</option><option>שדות מיכה</option><option>שדות ים</option><option>שגב-שלום</option><option>סגולה</option><option>שניר</option><option>שעב</option><option>שעל</option><option>שעלבים</option>
  <option>שער אפרים</option><option>שער העמקים</option><option>שער הגולן</option><option>שער מנשה</option><option>שער שומרון</option><option>שדמות דבורה</option><option>שדמות מחולה</option><option>שפיר</option><option>שחר</option><option>שחרות</option>
  <option>שלווה במדבר</option><option>שלווה</option><option>שמרת</option><option>שמיר</option><option>שני</option><option>שקד</option><option>שרונה</option><option>שרשרת</option><option>שבי דרום</option><option>שבי שומרון</option><option>שבי ציון</option>
  <option>שאר ישוב</option><option>שדמה</option><option>שפרעם</option><option>שפיים</option><option>שפר</option><option>שייח' דנון</option><option>שכניה</option><option>שלומי</option><option>שלוחות</option><option>שקף</option><option>שתולה</option>
  <option>שתולים</option><option>שיזף</option><option>שזור</option><option>שיבולים</option><option>שבלי - אום אל-גנם</option><option>שילת</option><option>שילה</option><option>שמעה</option><option>שמשית</option><option>נאות סמדר</option>
  <option>שלומית</option><option>שואבה</option><option>שוהם</option><option>שומרה</option><option>שומריה</option><option>שוקדה</option><option>שורשים</option><option>שורש</option><option>שושנת העמקים</option><option>צוקי ים</option>
  <option>שובל</option><option>שובה</option><option>סתריה</option><option>סופה</option><option>סולם</option><option>סוסיה</option><option>תעוז</option><option>טל שחר</option><option>טל-אל</option><option>תלמי ביל\"ו</option>
  <option>תלמי אלעזר</option><option>תלמי אליהו</option><option>תלמי יפה</option><option>תלמי יחיאל</option><option>תלמי יוסף</option><option>טלמון</option><option>טמרה</option><option>טמרה (יזרעאל)</option>
  <option>תראבין א-צאנע (שבט)</option><option>תראבין א-צאנע(ישוב)</option><option>תרום</option><option>טייבה</option><option>טייבה (בעמק)</option><option>תאשור</option><option>טפחות</option><option>תל עדשים</option>
  <option>תל אביב - יפו</option><option>תל מונד</option><option>תל קציר</option><option>תל שבע</option><option>תל תאומים</option><option>תל יצחק</option><option>תל יוסף</option><option>טללים</option><option>תלמים</option>
  <option>תלם</option><option>טנא</option><option>תנובות</option><option>תקוע</option><option>תקומה</option><option>טבריה</option><option>תדהר</option><option>תפרח</option><option>תימורים</option><option>תמרת</option>
  <option>טירת כרמל</option><option>טירת יהודה</option><option>טירת צבי</option><option>טירה</option><option>תירוש</option><option>תומר</option><option>רמת טראמפ</option><option>טובא-זנגריה</option><option>טורעאן</option>
  <option>תושיה</option><option>תובל</option><option>אודים</option><option>אום אל-פחם</option><option>אום אל-קוטוף</option><option>אום בטין</option><option>עוקבי (בנו עוקבה)</option><option>אורים</option><option>אושה</option>
  <option>עוזה</option><option>עוזייר</option><option>ורדון</option><option>ורד יריחו</option><option>יעד</option><option>יערה</option><option>יעל</option><option>יד בנימין</option><option>יד חנה</option><option>יד השמונה</option>
  <option>יד מרדכי</option><option>יד נתן</option><option>יד רמב\"ם</option><option>יפיע</option><option>יפית</option><option>יגל</option><option>יגור</option><option>יהל</option><option>יכיני</option><option>יאנוח-ג'ת</option>
  <option>ינוב</option><option>יקיר</option><option>יקום</option><option>ירדנה</option><option>ירחיב</option><option>ירקונה</option><option>יסעור</option><option>ישרש</option><option>יתד</option><option>יבנה</option><option>יבנאל</option>
  <option>יציץ</option><option>יעף</option><option>ידידה</option><option>כפר ידידיה</option><option>יחיעם</option><option>יהוד-מונוסון</option><option>ירוחם</option><option>ישע</option><option>יסודות</option><option>יסוד המעלה</option>
  <option>יבול</option><option>יפעת</option><option>יפתח</option><option>ינון</option><option>יראון</option><option>ירכא</option><option>ישעי</option><option>ייט\"ב</option><option>יצהר</option><option>יזרעאל</option><option>יודפת</option>
  <option>יונתן</option><option>יקנעם עילית</option><option>יקנעם (מושבה)</option><option>יושיביה</option><option>יטבתה</option><option>יובל</option><option>יובלים</option><option>זבארגה (שבט)</option><option>צפרירים</option><option>צפריה</option>
  <option>זנוח</option><option>זרזיר</option><option>זבדיאל</option><option>צאלים</option><option>צפת</option><option>זכריה</option><option>צלפון</option><option>זמר</option><option>זרחיה</option><option>זרועה</option><option>צרופה</option>
  <option>זיתן</option><option>זכרון יעקב</option><option>זמרת</option><option>ציפורי</option><option>זיקים</option><option>צבעון</option><option>צופר</option><option>צופית</option><option>צופיה</option><option>צוחר</option><option>זוהר</option>
  <option>צרעה</option><option>צובה</option><option>צופים</option><option>צור הדסה</option><option>צור משה</option><option>צור נתן</option><option>צור יצחק</option><option>צוריאל</option><option>צורית</option><option>צביה</option>";
}

add_action('wp_ajax_concepta_save_user_file', 'concepta_save_user_file');
add_action('wp_ajax_nopriv_concepta_save_user_file', 'concepta_save_user_file');

function concepta_save_user_file(){
  $ele = (isset($_FILES['logoUpload'])) ? $_FILES['logoUpload'] : $_FILES['logoUpload2'];
  if ( isset($ele) ) {
    $upload_dir = wp_upload_dir();        
    if ( !empty( $upload_dir['basedir'] ) ) {
      $user_dirname = $upload_dir['basedir'].'/eshkolot-offline-logo/';
      if ( !file_exists( $user_dirname ) ) {
        wp_mkdir_p( $user_dirname );
      }
      $file_name = $ele['name'];
      move_uploaded_file($ele['tmp_name'], $user_dirname .'/'. $file_name);
      update_user_meta($_POST['user_id'], 'logo_image', '/wp-content/uploads/eshkolot-offline-logo/'. $file_name);
    }
  }
}

?>
