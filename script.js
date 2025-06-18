jQuery(document).ready(function ($) {
  deleteCookies();
  groupId = Object.keys(groupsObj).length;
  var childId = `${groupId}_0`;
  var total = 0;
  var flag = true;
  var logoFlag = false;
  var childTable = $('#child_table').DataTable({
    rowReorder: true, rowReorder: { selector: '.drag-rows' },
    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/he.json' },
    columns: [
      { title: 'מס.' },
      { title: 'שם פרטי' },
      { title: 'שם משפחה' },
      { title: 'סיסמא - תעודת זהות' }],
  });

  $(document).on('change', '#logoUpload, #logoUpload2', function (e) {
    const [file] = e.target.files;
    if (file) {
      jQuery('.logoImg').css('backgroundImage', "url('" + URL.createObjectURL(file) + "')");
      jQuery('.logoImg').css('border-radius', '0px');
      jQuery('.logoImg').css('border', 'none');
      jQuery('.logoImg img').hide();
      jQuery('#organization_2 .logo').children(".logoTitle1, .logoTitle2, .logoImg img").hide();
      jQuery('.logo').css('border', 'none');
      jQuery('#organization_2 .logoImg').css('left', '0px');
      jQuery('#organization_2 .logoImg').css('position', 'absolute');
      jQuery('.logo-upload label:last-child').hide();
      uploadImage($(e.currentTarget).parents('form').attr('class'));
      logoFlag = true;
    }
  });

  $(document).on('mouseover, mouseenter', '.logo-upload, .logo-upload2', function () {
    if (logoFlag) {
      jQuery('.pencil-icon').show();
    }
  });

  $(document).on('mouseleave', '.logo-upload, .logo-upload2', function () {
    jQuery('.pencil-icon').hide();
  });

  $(document).on('click', '#newGroup', function () {
    reset();
    groupId = Object.keys(groupsObj).length;
    groupList.push(groupId);
    initgroupsObj(groupId);
    saveOrganizationDetails(groupId);
    $('.popupEditDetils').hide();
    $('.pay-and-downloads').attr('data-groupid', groupId);
    $('.elementor-location-header').hide();
  });

  function initgroupsObj(groupId) {
    groupsObj[groupId] = {};
    groupsObj[groupId]['payment'] = false;
    groupsObj[groupId]['students'] = {};
    groupsObj[groupId]['courses'] = {};
    groupsObj[groupId]['courses']['adapted'] = {};
    groupsObj[groupId]['courses']['all'] = [];
  }

  $(document).on('click', '.next, .prev', function (e) {
    dataEvent = $(e.currentTarget).data('event');
    elementClass = $(e.currentTarget).attr('class');
    if (isValidation(dataEvent, elementClass)) {
      hideFrame();
      switch (dataEvent) {
        case 'organization-details':{
          frameIndex = 1;
          break;
        }
        case 'students':{
          saveGroupDetails(groupId);
          setGroupName(groupId);
          $('#organization_4 .present_icon').removeClass("present_icon");
          $($('#organization_4 .icon_cls')[1]).addClass("present_icon");
          if (flag) createChildTable(groupId);
          $('page-id-124203 .ast-container').css('background', '#FFF');
          $('#organization_4').parent('.background-opacity').show();
          $('.closeBtn').hide();
          frameIndex = 4;
          break;
        }
        case 'courses':{
          setGroupName(groupId);
          setStyle($(this), 'cursor: pointer;');
          removeEmptyStudents(groupId);
          if (isPayment(groupId)){
            saveStudentsDetails(groupId, true);
            courseInit(groupId, true, 'all');
            $('#organization_5 #title_icons, #organization_5 #edit_dtl').hide();
            $('#organization_5 .prev').addClass(`group-${groupId}`);
            setEnableIfSelectedCourse(groupId);
          }
          else {
            saveStudentsDetails(groupId);
            courseInit(groupId, false, 'all');
            $('#organization_5 .present_icon').removeClass("present_icon");
            $($('#organization_5 .icon_cls')[2]).addClass("present_icon");
            $('#adapted').removeClass('active');
            $('#all').addClass('active');
            setStyle($('.child_lst'), 'display: none;');
            $('.closeBtn').hide();
          }
          createChildrenList(groupId, false);
          setAdaptedCourses(groupId);
          $('.courses_list').attr('data-groupid', groupId);
          $('.page-id-124203 .ast-container').css('background', '#FFF');
          $('#organization_5').parent('.background-opacity').show();
          frameIndex = 5
          break;
        }
        case 'groups':{
          if (frameIndex == 1){
            organizationName = $('.organization-name input').val();
            $('#organization_2 .header span').html("'" + organizationName + "'");
            setNonDisable($(this));
          }
          else {
            saveCoursesDetails(groupId);
            alertNoCoursesSelected(groupId);
            if (frameIndex == 5 && elementClass == 'prev') {
              createGroup(groupId);
              jQuery('.proceed-to-payment').show();
            }
            setExistGroup(groupId);
            reset();
          }
          $('.page-id-124203 .ast-container').css('background', '#FAFAFA');
          frameIndex = 2;
          break;
        }
        case 'shopping-cart':{
          saveOrganizationDetails(groupId);
          saveGeneralDetailsInCookie(groupId);
          saveStudentsDetailsInCookie(groupId);
          // delCookie(`shopping_offline_${user_id}_${groupId}_`);
          saveCoursesDetails(groupId, true);
          alertNoCoursesSelected(groupId);
          shoppingCart();
          $('.background-opacity').hide();
          $('.page-id-124203 .ast-container').css('background', '#FAFAFA');
          frameIndex = 6;
          break;
        }
        case 'group-details':{
          $('.page-id-124203 .ast-container').css('background', '#FFF');
          setDisable($('#organization_5 .closeBtn'));
          $('#organization_3').parent('.background-opacity').show();
          $('#organization_3 .closeBtn').show();
          frameIndex = 3;
          break;
        }
      }
      $('#organization_' + frameIndex).show();
    }
    else {
      switch (dataEvent) {
        case 'groups': {
          if ($('.organization-name input').val() == '') setBorder($('.organization-name input'), 'red');
          if ($('.organization-symbol input').val() == '') setBorder($('.organization-symbol input'), 'red');
          break;
        }
        case 'students': {
          groupName = $('#group-name');
          gender = $('#gender input[type="radio"]');
          ages = $('.ages input[type="radio"]');
          if (groupName.val().length == 0) setBorder(groupName, 'red');
          if (!gender.is(':checked')) 
            for(item of gender){
              $(item).css('border-color','red !important;');
              // setBorder($(item), 'red !important;')
            }
          // gender.forEach((item) => {setBorder(item, 'red')});
          if (!ages.is(':checked')) 
          for(item of ages){
            setBorder($(item), 'red !important;')
          }
          // ages.forEach((item) => {setBorder(item, 'red')});
          break;
        }
      }
    }
  });

  $(document).on('click', '.closeBtn', function () {
    hideFrame();
    removeEmptyStudents(groupId);
    reset();
    $('#organization_2').show();
    $('.elementor-location-header').show();
  });

  function isValidation(event, elementClass){
    var validation = true;
    switch (event){
      case 'students':
      case 'save-group-detail': {
        groupName = $('#group-name').val();
        gender = $('#gender input[type="radio"]:checked').val();
        ages = $('.ages input[type="radio"]:checked').val();
        validation = (groupName.length) && (gender != undefined) && (ages != undefined);
        break;
      }
      case 'courses':
      case 'update-students': {
        if (elementClass.includes('next') || elementClass == 'saveAndClose'){
          validation = isTableValid($('#child_table tbody tr'));
        }
        else if (elementClass == 'prev'){
          validation = $('#organization_5 input[type=checkbox]:checked').length > 0;
        }
        break;
      }
      case 'groups':{
        if (elementClass == 'next')
          validation = ($('.agree input[type=checkbox]').is(":checked")) && ($('.organization-name input').val() != '') && ($('.organization-symbol input').val() != '');
        break;
      }
    }
    return true;//delete!!!!!!!!
    return validation;
  }

  function hideFrame(){
    for (i = 1; i < 7; i++)
      $('#organization_' + i).hide();
  }

  $(document).on('click', '.agree input[type=checkbox]', function () {
    $(this).is(":checked") ? setNonDisable($('#organization_1 .next')) : setDisable($('#organization_1 .next'));
  });

  $(document).on('focus', '.organization-name input, .organization-symbol input, #group-name', function () {
    setBorder($(this), 'none');
  });

  $(document).on('focus', '.editOrganization3 #gender, .editOrganization3 .ages', function () {
    setBorder($(this).find('input'), '#5956DA');
  });

  function createChildTable(groupId) {
    rows = [];
    // if (isPayment(groupId)){
      childId = initChildId(groupId);
    // }
    for (var i = 0; i < 6; i++) {
      rows.push(addRow(childId, String(i + 1)));
      groupsObj[groupId]['students'][childId] = {};
      initChildCourses(groupId, childId);
      childId = incrementChildId(childId);
    }
    childTable.rows.add(rows).draw();
    setPosition();
  }

  function addRow(dataId, num, name = '', family = '', id = '') {
    return ['<td data-id="' + dataId + '"><p class="auto_inc">' + num + '.</p></td>',
    '<td><div class="name-container"><input class="child_name" value="' + name + '" required></div></td>',
    '<td><input class="family_name" value="' + family + '" required></div></td>',
    '<td><div class="id-container"><input class="td_id" type="text" value="' + id + '" maxlength="9"><svg class="icon-delete" xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 20 17" fill="none"><path d="M18.5017 8.5C18.5017 12.8916 14.7227 16.5 9.99985 16.5C5.27703 16.5 1.49805 12.8916 1.49805 8.5C1.49805 4.10836 5.27703 0.5 9.99985 0.5C14.7227 0.5 18.5017 4.10836 18.5017 8.5Z" stroke="#2D2828"/><line x1="5.80273" y1="8.18945" x2="14.6045" y2="8.18945" stroke="black"/></svg><div class="drag-rows"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="17" viewBox="0 0 10 17" fill="none"><ellipse cx="2.6715" cy="1.7385" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="2.6715" cy="8.50022" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="2.6715" cy="15.2619" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="1.7385" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="8.50022" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="15.2619" rx="1.8004" ry="1.7385" fill="#2D2828"/></svg></div></div></td>'];
  }

  $(document).on('click', '.icon-delete', function (event) {
    //groupId = ;
    if (childTable.rows().count() > 1) {
      id = $(childTable.row($(this).parents('tr')).data()[0]).attr("data-id");
      delete groupsObj[groupId]['students'][id];
      delete groupsObj[groupId]['courses']['adapted'][id];
      childTable.row($(this).parents('tr')).remove().draw();
      childTable.rows().every(function (rowIdx) {
        prevDataId = $(this.cell(rowIdx, 0).data()).attr("data-id");
        this.cell(rowIdx, 0).data('<td data-id="' + prevDataId + '"><p class="auto_inc">' + String(rowIdx + 1) + '.</p></td>');
      });
      setPosition();
    }
  });

  $(document).on('mouseover', '.icon-delete', function () {
    if (childTable.rows().count() <= 1) {
      $(this).attr("disabled", true);
      $(this).css('cursor', 'not-allowed');
    }
  });

  $(document).on('click', '.add_child', function () {
    // groupId = ??;
    var rowsCount = childTable.rows().count();
    childTable.row.add(addRow(childId, String(rowsCount + 1))).draw();
    groupsObj[groupId]['students'][childId] = {};
    childId = incrementChildId(childId);
    $('#child_table .icon-delete').attr("disabled", false);
    $('#child_table .icon-delete').css('cursor', 'pointer');
    setPosition(true);
  });

  function setPosition(flag = false){
    var newHeight = $('#wrap_table').height();
    if (newHeight == 0)
      newHeight = 470;
    $('#bottom-btn').css('top', newHeight + 360);
    if (flag) {
      $('#organization_4 .forward-back-btn').css('top', newHeight + 330);
    }
  }

  $(document).on('change', '#excelUpload', function (e) {
    //groupId = ??;
    childTable.rows().every(function () {
      var childId = $(this.data()[0]).attr("data-id");
      delete groupsObj[groupId]['students'][childId];
      delete groupsObj[groupId]['courses']['adapted'][childId];
    });
    childTable.clear();
    readXlsxFile(this.files[0]).then(function (rows) {
      rows.shift();
      for (let i = 0; i < rows.length; i++) {
        childTable.row.add(addRow(childId, String(i + 1), rows[i][0], rows[i][1], rows[i][2])).draw();
        groupsObj[groupId]['students'][childId] = {};
        initChildCourses(groupId, childId);
        childId = incrementChildId(childId);
      }
    });
    saveStudentsDetails(groupId);
    $('#organization_4 .next').css('cursor', 'pointer');
  });

  function isTableValid(arr){
    warnText = ['שדה חובה', 'נא להזין ת"ז תקינה', 'אין להזין ערכים כפולים'];
    ids = [];
    count = 0;
    valid = 0;
    for(let i = 0; i < arr.length; i++){
      inputs = $(arr[i]).find('td input');
      fname = $(inputs[0]).val();
      lname = $(inputs[1]).val();
      id = $(inputs[2]).val();

      if( count >= 3 && count % 3 == 0 && ((fname == '' && lname == '' && id == '') || (fname == warnText[0] && lname == warnText[0] && id == warnText[0]))){
        valid = 0;
        continue;
      }

      if (fname == '' || fname == warnText[0]) {
        valid++;
        $(arr[i]).find('.child_name').val(warnText[0]).css('color', 'red').css('font-style', 'italic');
      }
      else {
        count++;
      }

      if (lname == '' || lname == warnText[0]) {
        valid++;
        $(arr[i]).find('.family_name').val(warnText[0]).css('color', 'red').css('font-style', 'italic');
      }
      else {
        count++;
      }

      if (id == '' || id == warnText[0]) {
        valid++;
        $(arr[i]).find('.td_id').val(warnText[0]).css('color', 'red').css('font-style', 'italic');
      }
      else if (!idValidation(id)) {
        valid++;
        $(arr[i]).find('.td_id').val(warnText[1]).css('color', 'red').css('font-style', 'italic');
      }
      else if (ids.includes(id)) {
        valid++;
        $(arr[i]).find('.td_id').val(warnText[2]).css('color', 'red').css('font-style', 'italic');
      }
      else {
        ids.push(id);
        count++;
      }
    }
    return valid == 0;
  }

  $(document).on('focus', '.child_name, .family_name, .td_id', function () {
    if (['שדה חובה', 'נא להזין ת"ז תקינה', 'אין להזין ערכים כפולים'].indexOf($(this).val()) !== -1 ){
      $(this).val('').css('color', '#2D2828').css('font-style', 'normal');
    }
  });

  function idValidation(id) {
    id = String(id).trim();
    if (id.length > 9 || isNaN(id)) return false;
    id = id.length < 9 ? ("00000000" + id).slice(-9) : id;
    return Array.from(id, Number).reduce((counter, digit, i) => {
      const step = digit * ((i % 2) + 1);
      return counter + (step > 9 ? step - 9 : step);
    }) % 10 === 0;
  }

  $(document).on('click', '#all', function () {
    groupId = $(this).parents('.courses_list_wrap').data('groupid');
    // if ($('.child_now').data('id'))
    if (groupsObj[groupId]['courses']['adapted'].length) 
      groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]['private'] = getCheckedCourses(groupId);
    $('.child_now').removeClass('child_now');
    if (isPayment(groupId)){
      allPaidCourses = getAllPaidCourses(groupId);
      setCoursesChecked('all', groupsObj[groupId]['courses']['all'], allPaidCourses, true);
    }
    else
      setCoursesChecked('all', groupsObj[groupId]['courses']['all']);
    $('#adapted').removeClass('active');
    $(this).addClass('active');
    setEnableIfSelectedCourse(groupId);
    setStyle($('.courses_list'), 'margin-top: 30px !important;');
    setStyle($('.child_lst'), 'display: none;');
  });

  $(document).on('click', '#adapted', function () {
    groupId = $(this).parents('.courses_list_wrap').data('groupid');
    $('.child_now').removeClass('child_now');
    $('.child_lst span:first-child').addClass('child_now');
    groupsObj[groupId]['courses']['all'] = getCheckedCourses(groupId);
    setAllCoursesForChildren(groupId);
    enableCourses();
    if (isPayment(groupId))
      setCoursesChecked('adapted', groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')], groupsObj[groupId]['paidCourses']['adapted'][$('.child_now').data('id')], true);
    else
      setCoursesChecked('adapted', groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]);
    setDisableSelectedCourses(groupId);
    $('#all').removeClass('active');
    $(this).addClass('active');
    setEnableIfSelectedCourse(groupId);
    setStyle($('.child_lst'), 'display: inline-block;');
    if (toDisplayRightButton()) {
      setVisible($('#right-button'));
    }
    setStyle($('.courses_list'), 'margin-top: 0px !important;');
  });

  $(document).on('click', '.child', function () {
    groupId = $(this).parents('.courses_list_wrap').data('groupid');
    if (groupsObj[groupId]['courses']['adapted'].length) 
      groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]['private'] = getCheckedCourses(groupId);
    $('.child_now').removeClass('child_now');
    $(this).addClass('child_now');
    enableCourses();
    if (isPayment(groupId))
      setCoursesChecked('adapted', groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')], groupsObj[groupId]['paidCourses']['adapted'][$('.child_now').data('id')], true);
    else
      setCoursesChecked('adapted', groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]);
    setDisableSelectedCourses(groupId);
    setEnableIfSelectedCourse(groupId);
    });

  function getCheckedCourses(groupId) {
    checked = $('#organization_5 input[type=checkbox]:checked');
    checkedIds = jQuery.map(jQuery.makeArray(checked), function(val) {  
      return $(val).data('id');  
    });
    if(isPayment(groupId) || getChoiceType() == 'adapted')
      checkedIds = checkedIds.filter((id) => !getIrrelevantCourses().includes(id));
    return checkedIds;
  }

  function getIrrelevantCourses() {
    irrelevantCourses = $('.cbx[style*="pointer-events: none"]');
    irrelevantCourses = jQuery.map(jQuery.makeArray(irrelevantCourses), function(val) {  
      return Number($(val).attr('for').split('-')[1]);  
    });
    return irrelevantCourses;
  }

  function setCoursesChecked(type, courses = [], paidCourses = [], isPayment = false) {
    enableCourses();
    propChecked(type, courses, false);
    if (isPayment){
      paidCourses = getPaidCourses(type, courses, paidCourses);
      propChecked(type, paidCourses, isPayment);
    } 
  }

  function propChecked(type, courses, isPayment) {
    if (type == 'all') {
      if (courses.length)
        performPropChecked(courses, isPayment);
    }
    else {
      if ((courses.length || Object.keys(courses).length) && (courses['all'] && courses['all'].length)) performPropChecked(courses['all'], isPayment);
      if ((courses.length || Object.keys(courses).length) && (courses['private'] && courses['private'].length)) performPropChecked(courses['private'], isPayment);
      // if ((courses.length || Object.keys(courses).length) && (courses[0] && courses[0]['private'].length)) performPropChecked(courses[0]['private'], false);
    }
  }

  function performPropChecked(courses, toDisable){
    for (var i = 0; i < courses.length; i++) {
      $('input[type=checkbox][data-id=' + courses[i] + ']').prop("checked", true);
      if (toDisable) disablePaidCourses(courses[i]);
    }
  }

  function getPaidCourses(type, courses, paidCourses){
    var returnCourses;
    if (type == 'all') {
      returnCourses = [];
      if (paidCourses.length)
      returnCourses = paidCourses.filter( ( item ) => !courses.includes( item ) );
    }
    else {
      returnCourses = {};
      if ((paidCourses.length || Object.keys(paidCourses).length) && (paidCourses['all'] && paidCourses['all'].length))
        returnCourses['all'] = paidCourses['all'].filter( ( item ) => !courses['all'].includes( item ) );  
      if ((paidCourses.length || Object.keys(paidCourses).length) && (paidCourses['private'] && paidCourses['private'].length))
        returnCourses['private'] = paidCourses['private'].filter((item) => !courses['private'].includes(item));  
    }
    return returnCourses;
  }

  $(document).on('click', '.courses_list input[type=checkbox]', function (event) {
    groupId = $(event.target).parents('.courses_list').data('groupid');
    checkedCourses = getCheckedCourses(groupId);
    choiceType = getChoiceType();
    if (choiceType == 'all'){
      groupsObj[groupId]['courses']['all'] = checkedCourses;
      setAllCoursesForChildren(groupId);
    }
    else{
      if (!Object.keys(groupsObj[groupId]['courses']['adapted']).length)
        initChildCourses(groupId, $('.child_now').data('id'));
      groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]['private'] = checkedCourses;
    }
    setEnableIfSelectedCourse(groupId);
  });

  function setEnableIfSelectedCourse(groupId) {
    toEnable = false;
    toEnable = groupsObj[groupId]['courses']['all'].length;
    if (!toEnable) toEnable = getCheckedCourses(groupId).length;
    if (!toEnable) {
      if (Object.keys(groupsObj[groupId]['courses']['adapted']).length){
        for (const key in groupsObj[groupId]['courses']['adapted']) {
          if(groupsObj[groupId]['courses']['adapted'][key]['all'].length || groupsObj[groupId]['courses']['adapted'][key]['private'].length) {
            toEnable = true;
            break;
          }
        }
      }
    }
    if (toEnable) {  
      $('#lbl_added').fadeIn("slow");
      setNonDisable($('#organization_5 .next'));
      setNonDisable($('#organization_5 .prev'));
    }
    else {
      $('#lbl_added').fadeOut("slow");
      setDisable($('#organization_5 .next'));
      setDisable($('#organization_5 .prev'));
    }
    setNonDisable($('#organization_5 .closeBtn'));
  }

  $(document).on('click', '.courses_list svg', function () {
    courseItems = $(this).parent('dt').next('dl');
    if ($(this).parent('dt').text().includes('קורסים')){
      courseItems = $(this).parent('dt').nextUntil('.dt-course-header');
    }
    if (courseItems.is(':visible')){
      courseItems.hide();
      $(this).css('transform', 'rotate(90deg)');
    }
    else{
      courseItems.show();
      $(this).css('transform', 'rotate(0deg)');
    }
  });

  function createGroup(groupId) {
    jQuery('#organization_2 .wrapNewGroup').prepend(`<div class="existGroup" data-groupid="${groupId}">
        <div class="active">
          <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none">
            <circle cx="3" cy="3" r="3" fill="#C8C9CE"/>
          </svg>
          <span class="awaiting-payment">ממתין לתשלום</span>
        </div>
        <div class="existGroupEdit">
          <svg xmlns="http://www.w3.org/2000/svg" width="5" height="20" viewBox="0 0 5 20" fill="none">
            <circle cx="2.125" cy="2" r="2" fill="#2D2828"/>
            <circle cx="2.125" cy="10" r="2" fill="#2D2828"/>
            <circle cx="2.125" cy="18" r="2" fill="#2D2828"/>
          </svg>
        </div>
        <a href="/personal-area/"></a>
        <p class="existGroupName"></p>
        <p class="existGroupCount"></p>
        <div class="popupEditDetils" style="display: none;">
          <div class="popupGroup">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
              <path d="M0.188631 11.973L0.190865 11.9753C0.25011 12.0349 0.320567 12.0823 0.398186 12.1147C0.475805 12.147 0.559055 12.1637 0.643148 12.1638C0.713908 12.1638 0.784179 12.1521 0.851159 12.1293L4.49793 10.8922L11.4958 3.89429C11.9236 3.46645 12.164 2.88619 12.1639 2.28116C12.1639 1.67613 11.9235 1.0959 11.4957 0.668095C11.0679 0.240295 10.4876 -2.64859e-05 9.88257 2.18939e-09C9.27755 2.64902e-05 8.69731 0.240398 8.26951 0.668236L1.2716 7.66615L0.0346253 11.3128C-0.00433934 11.4262 -0.0105588 11.5483 0.01668 11.6651C0.0439189 11.7818 0.103513 11.8886 0.188631 11.973ZM8.85063 1.24931C9.12469 0.977333 9.49536 0.825043 9.88147 0.825785C10.2676 0.826526 10.6377 0.980238 10.9107 1.25326C11.1837 1.52629 11.3374 1.89637 11.3381 2.28248C11.3389 2.66859 11.1866 3.03926 10.9146 3.31332L9.99436 4.23355L7.93035 2.16954L8.85063 1.24931ZM1.98834 8.11157L7.34928 2.75061L9.41329 4.81462L4.05232 10.1756L0.928714 11.2352L1.98834 8.11157Z" fill="black"/>
            </svg>
            עריכת פרטי קבוצה
          </div>
          <div class="popupStudent">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14" fill="none">
              <path d="M12.0916 8.27746L10.3493 7.13748L11.0201 5.90765C11.1819 5.60057 11.2667 5.25877 11.2671 4.91167V2.96206C11.2685 2.41386 11.1097 1.87721 10.8103 1.418C10.5109 0.958785 10.0839 0.596999 9.58178 0.377067C9.07965 0.157135 8.52421 0.0886308 7.98368 0.179967C7.44315 0.271304 6.94106 0.518504 6.53906 0.891214L7.12795 1.52692C7.4065 1.26938 7.75415 1.09871 8.12827 1.03584C8.5024 0.972969 8.88673 1.02063 9.23416 1.17299C9.58159 1.32535 9.87702 1.57577 10.0842 1.89356C10.2914 2.21135 10.4014 2.58269 10.4006 2.96206V4.91167C10.4005 5.11378 10.3521 5.31295 10.2594 5.49255L9.20565 7.4245L11.6172 9.00245C11.7763 9.10662 11.907 9.24893 11.9972 9.41644C12.0874 9.58394 12.1343 9.77133 12.1336 9.96157V11.4104H10.184V12.2769H13.0001V9.96155C13.001 9.62737 12.9183 9.29827 12.7597 9.00416C12.601 8.71004 12.3713 8.46024 12.0916 8.27746Z" fill="black"/>
              <path d="M8.19219 9.14393L6.44992 8.00395L7.12072 6.77412C7.28252 6.46704 7.36728 6.12524 7.36772 5.77814V3.82853C7.36717 3.08124 7.07021 2.36468 6.542 1.83606C6.01379 1.30744 5.29747 1.00993 4.55018 1.00879C2.99815 1.00879 1.73551 2.27374 1.73551 3.82853V5.77814C1.73385 6.12508 1.81769 6.46709 1.97962 6.77393L2.65407 8.01042L0.921923 9.14393C0.642482 9.32643 0.413035 9.57581 0.254404 9.86946C0.0957732 10.1631 0.0129804 10.4917 0.013539 10.8255L0 13.1433H9.10071V10.828C9.10159 10.4938 9.01894 10.1647 8.86027 9.87063C8.7016 9.57651 8.47195 9.32671 8.19219 9.14393ZM8.23422 12.2769H0.871531L0.879925 10.828C0.879271 10.6378 0.926173 10.4504 1.01637 10.2829C1.10656 10.1154 1.23719 9.97306 1.39638 9.86889L3.79771 8.29758L2.74029 6.35902C2.64862 6.17915 2.60121 5.98001 2.602 5.77814V3.82853C2.602 3.31146 2.80741 2.81556 3.17303 2.44994C3.53865 2.08432 4.03455 1.87891 4.55162 1.87891C5.06869 1.87891 5.56458 2.08432 5.9302 2.44994C6.29582 2.81556 6.50123 3.31146 6.50123 3.82853V5.77814C6.50115 5.98025 6.45273 6.17942 6.36002 6.35902L5.30625 8.29097L7.71776 9.86892C7.87695 9.97309 8.00757 10.1154 8.09777 10.2829C8.18796 10.4504 8.23487 10.6378 8.23422 10.828V12.2769Z" fill="black"/>
            </svg>
            עריכת פרטי תלמידים
          </div>
          <div class="popupCourse">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
              <path d="M9.74888 8.84092L6.49888 10.669L3.24888 8.84092V6.90943L2.32031 6.39355V9.38396L6.49888 11.7344L10.6775 9.38396V6.39355L9.74888 6.90943V8.84092Z" fill="black"/>
              <path d="M6.5 0L0 3.37037V4.17515L6.5 7.78616L12.0714 4.69103V7.25513H13V3.37037L6.5 0ZM11.1429 4.14465L10.2143 4.6605L6.5 6.72411L2.78571 4.6605L1.85714 4.14465L1.21356 3.78709L6.5 1.04598L11.7864 3.78709L11.1429 4.14465Z" fill="black"/>
            </svg>
          קורסים ומסלולים
          </div>
          <div class="popupDelete" data-groupid="${groupId}">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13" height="13" viewBox="0 0 13 13" fill="none">
              <rect width="13" height="13" fill="url(#pattern0)"/>
              <defs>
                <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#image0_5686_3787" transform="scale(0.00195312)"/>
                </pattern>
                <image id="image0_5686_3787" width="512" height="512" xlink:href="data:image/png;base64,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"/>
              </defs>
            </svg>
            מחיקת קבוצה
          </div>
          <div class="popup-overlay" data-groupid=${groupId}>
            <div class="popup-content">
              <p>האם אתה בטוח שתרצה למחוק?</p>
              <div class="delete-group-btn">
                <button class="cancel">ביטול</button>
                <button class="approval">אישור</button>
              </div>
            </div>
          </div>
        </div>`);
    saveGroupDetails(groupId);
    setExistGroup(groupId);
  }

  function createChildrenList(groupId, isPayment) {
    $('.courses_list_wrap').attr('data-groupid', groupId);
    students = groupsObj[groupId]['students'];
    for (const key in students) {
      $('.child_lst #outer').append(`<span class="child" data-id="${key}">${students[key]['childName']} ${students[key]['lastName']}</span>`);
    }
    // if (isPayment){
    //   students = groupsObj[groupId]['students']['adapted'];
    //   for (const key in paidStudents) {
    //     $('.child_lst #outer').append(`<span class="child" data-id="${key}">${students[key]['childName']} ${students[key]['lastName']}</span>`);
    //   }
    // }
    $('.child_lst  #outer span:first-child').addClass('child_now');
  }

  function setAdaptedCourses(groupId){
    students = groupsObj[groupId]['students'];
    for (const key in students) {
      groupsObj[groupId]['courses']['adapted'][key] ??= [];
    }
  }

  $(document).on('click', '.existGroupEdit', function () {
    elem = $(this).siblings('.popupEditDetils');
    elem.is(":visible") ? elem.hide() : elem.show();
    if ($('.popupEditDetils').is(":visible")) {
      $('.popupEditDetils').hide();
      elem.show();
    }
  });

  $(document).on('click', '.popupGroup, .popupStudent, .popupCourse', function (event) {
    reset();
    $(this).parent().hide();
    $(this).parents('.ast-container').css('background-color', 'white');
    $('#organization_2').hide();
    groupId = $(event.target).parents('.existGroup').data('groupid');
    elem = $(this).attr('class');
    current = '';
    switch (elem) {
      case 'popupGroup': {
        groupInit(groupId);
        current = '#organization_3';
        $(`${current} .forward-back-btn`).append(`<button class="saveAndClose" data-event="save-group-detail" data-groupid="${groupId}">שמירה</button>`);
        $(`${current} .next, ${current} #title_icons, ${current} #edit_dtl`).hide();
        break;
      }
      case 'popupStudent': {
        studentInit(groupId);
        setGroupName(groupId);
        current = '#organization_4';
        $(`${current} .forward-back-btn`).append(`<button class="saveAndClose" data-event="update-students" data-groupid="${groupId}">שמירה</button>`);
        $(`${current} .next, ${current} .prev, ${current} #title_icons, ${current} #edit_dtl`).hide();
        break;
      }
      case 'popupCourse': {
        createChildrenList(groupId, false);
        // setAdaptedCourses(groupId);
        courseInit(groupId, false, 'all');
        setGroupName(groupId);
        setEnableIfSelectedCourse(groupId);
        $('.courses_list').attr('data-groupid', groupId);
        current = '#organization_5';
        $(`${current} #title_icons, ${current} #edit_dtl`).hide();
        $(`${current} .next`).addClass(`group-${groupId}`);
        $(`${current} .prev`).addClass(`group-${groupId}`);
        break;
      }
    }
    $(current).parent('.background-opacity').show();
    $('.closeBtn').show();
    $(`${current} .header`).addClass('popup-header');
    $(current).addClass('popup-active');
    $(current).show();
  });

  $(document).on('click', '.popupDelete', function (event) {
    $(".popup-overlay, .popup-content").addClass("delete-active");
  });

  $(document).on('click', '.popupGroupDetails, .popupEditStudent, .popupAddStudent, .popupAddCourse', function (event) {
  	debugger
    $(this).parent().hide();
    $(this).parents('.ast-container').css('background-color', 'white');
    $('.elementor-location-header').hide();
    $('#organization_2').hide();
    groupId = $(event.target).parents('.existGroup').data('groupid');
    elem = $(this).attr('class');
    current = '';
    switch (elem) {
      case 'popupGroupDetails': {
        groupInit(groupId);
        current = '#organization_3';
        $(`${current} .forward-back-btn`).append(`<button class="saveAndClose" data-event="save-group-detail" data-groupid="${groupId}">שמירה</button>`);
        $(`${current} .next, ${current} #title_icons, ${current} #edit_dtl`).hide();
        break;
      }
      case 'popupEditStudent':{
        studentInit(groupId);
        setGroupName(groupId);
        current = '#organization_4';
        $('.add_child').hide();
        $('#excel').hide();
        setDisabledPaidStudents(groupId);
        setPosition(true);
        $(`${current} .forward-back-btn`).append(`<button class="saveAndClose" data-groupid="${groupId}" data-event="update-students">שמירה</button>`);
        $(`${current} .next, ${current} .prev, ${current} #title_icons, ${current} #edit_dtl`).hide();
        break;
      }
      case 'popupAddStudent':{
        createChildTable(groupId);
        setGroupName(groupId);
        $('.pay-and-downloads').attr('data-groupid', groupId);
        current = '#organization_4';
        $(`${current} .next`).addClass(`add-students group-${groupId}`);
        $(`${current} .prev, ${current} #title_icons, ${current} #edit_dtl`).hide();
        break;
      }
      case 'popupAddCourse': {
        createChildrenList(groupId, true);
        courseInit(groupId, true, 'all');
        setGroupName(groupId);
        setEnableIfSelectedCourse(groupId);
        $('.courses_list').attr('data-groupid', groupId);
        $('.pay-and-downloads').attr('data-groupid', groupId);
        current = '#organization_5';
        $(`${current} #title_icons, ${current} #edit_dtl`).hide();
        $(`${current} .prev`).addClass(`group-${groupId}`);
        break;
      }
	  }
    $('.closeBtn').show();
    $(current).parent('.background-opacity').show();
    $(current).show();
  });
	
  $(document).on('click', '.delete-group-btn .cancel, .delete-group-btn .approval', function (event) {
    if ($(this).attr('class') == 'approval') {
      groupId = $(this).parents('.popup-overlay').data('groupid');
      $(`.existGroup[data-groupid=${groupId}]`).remove();
      groupList.pop();
      delCookie(`_${user_id}_${groupId}`);
      groupsObj.splice(groupId, 1);
      for(index = groupId; index < Object.keys(groupsObj).length; index++) {
        currentId = parseInt(Object.keys(groupsObj[index]['students']).shift().split('_')[1]);
        for(i = 0; i < Object.keys(groupsObj[index]['students']).length; i++) {
          groupsObj[index]['students'][`${index}_${currentId}`] = groupsObj[index]['students'][`${index+1}_${currentId}`];
          delete groupsObj[index]['students'][`${index+1}_${currentId}`];
          groupsObj[index]['courses']['adapted'][`${index}_${currentId}`] = groupsObj[index]['courses']['adapted'][`${index+1}_${currentId}`];
          delete groupsObj[index]['courses']['adapted'][`${index+1}_${currentId}`];
          currentId++;
        }
        $(`.existGroup[data-groupid=${index+1}]`).attr('data-groupid', index);
      }
      if (!$('.existGroup').length) {
        $('.wrapNewGroup').css('column-gap', '0px');
      }
    }
    $(".popup-overlay, .popup-content").removeClass("delete-active");
    $(this).parents('.popupEditDetils').hide();
    $('#organization_2').show();
  });

  function reset() {
    $('#group-name').val('');
    $('.editOrganization3 input[type="radio"]').prop('checked', false);
    childTable.clear().draw();
    flag = true;
    $('#excelUpload').val('');
    setDisable($('#organization_5 .next'));
    setDisable($('#organization_5 .prev'));
    $('#lbl_added').fadeOut("slow");
    $('.prev, .next, #title_icons, #edit_dtl').show();
    $('.saveAndClose').remove();
    $('.closeBtn').hide();
    $('#organization_5 .next').removeClass(`group-${groupId}`);
    $('#organization_5 .prev').removeClass(`group-${groupId}`);
    $('#organization_5 .child_lst span').remove();
    setInvisible($('#left-button'));
    setInvisible($('#right-button'));
    $('.prevBtn').show();
    $('.icon-delete').show();
    $('.add_child').show();
    $('#excel').show();
    $('.courses_list').removeData('groupid');
    $('.courses_list_wrap').removeData('groupid');
    enableCourses();
    setStyle($('.courses_list'), 'margin-top: 30px !important;');
    $('#adapted').removeClass('active');
    $('#all').addClass('active');
    $('.background-opacity').hide();
    $('.popup-header').removeClass('popup-header');
    $('.popup-active').removeClass('popup-active');
  }

  function shoppingCart() {
    coursesSummery = {};
    for (const group in groupsObj) {
      coursesTotal = createCoursesTotal(group);
      for (const key in coursesTotal) {
        count = coursesTotal[key]['count'];
        names = coursesTotal[key]['names'].join(', ');
        if (!coursesSummery[key]) {
          coursesSummery[key] = {};
          coursesSummery[key]['count'] = 0;
          coursesSummery[key]['names'] = [];
        }
        coursesSummery[key]['count'] += count;
        coursesSummery[key]['names'].push(names);
      }
    }
    for (const key in coursesSummery) {
      count = coursesSummery[key]['count'];
      names = coursesSummery[key]['names'];
      createShoppingCartTable(key, names, count);
    }
    $('#total-price').html(total + '&nbsp;₪');
  }

  function createCoursesTotal(groupId) {
    coursesTotal = {};
    allCourses = groupsObj[groupId]['courses']['all'];
    for (i = 0; i < allCourses.length; i++) {
      if (!coursesTotal[allCourses[i]]) {
        coursesTotal[allCourses[i]] = {};
        coursesTotal[allCourses[i]]['count'] = 0;
        coursesTotal[allCourses[i]]['names'] = [];
      }
      coursesTotal[allCourses[i]]['count'] += Object.keys(groupsObj[groupId]['students']).length;//TODO: in case add students??
      coursesTotal[allCourses[i]]['names'].push(groupsObj[groupId]['groupName']);
    }
    adaptedCourses = groupsObj[groupId]['courses']['adapted'];
    for (key in adaptedCourses) {
      privateCourses = adaptedCourses[key]['private'];
      for (i = 0; i < privateCourses.length; i++) {
        if (!coursesTotal[privateCourses[i]]) {
          coursesTotal[privateCourses[i]] = {};
          coursesTotal[privateCourses[i]]['count'] = 0;
          coursesTotal[privateCourses[i]]['names'] = [];
        }
        coursesTotal[privateCourses[i]]['count'] += 1;
        fullName = `${groupsObj[groupId]['students'][key]['childName']} ${groupsObj[groupId]['students'][key]['lastName']}`;
        coursesTotal[privateCourses[i]]['names'].push(fullName);
      }
    }
    return coursesTotal;
  }

  function createShoppingCartTable(id, names, count) {
    course = $('.courses_list div[data-id=' + id + ']');
    courseName = course.data('name');
    coursePrice = course.data('price');
    totalPrice = Number(coursePrice) * count;
    total += totalPrice;
    icon = course.data('icon');
    courseNameWithIcon = icon ? courseName + icon : courseName;
    $('#table_course tbody').append('<tr><td>' + courseNameWithIcon + '</td><td>' + count + '</td><td id="">' + names + '</td><td class="td-price">' + totalPrice + '&nbsp;₪</td><td><svg class="btnDelete" data-id="' + id + '" xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none"><circle cx="18.0185" cy="18.0194" r="12.241" transform="rotate(-45.0624 18.0185 18.0194)" stroke="#2D2828"/><line x1="14.3629" y1="13.2632" x2="23.1816" y2="22.0627" stroke="black"/><line x1="13.6656" y1="22.0746" x2="22.4651" y2="13.2559" stroke="black"/></svg></td><tr>');
    cookieValue = { 'id': id, 'courseName': courseName, 'count': count };
    cookieName = `shopping_offline_summery_${user_id}_${id}`;
    setCookie(cookieName, JSON.stringify(cookieValue), 1);
  }

  $(document).on('click', '.btnDelete', function () {
    // groupId = 
    price = Number($(this).parent().siblings('.td-price').html().split('&nbsp;')[0]);
    total -= price;
    $('#total-price').html(total + '&nbsp;₪');
    $(this).closest('tr').remove();
    courseId = $(this).data('id');
    cookieName = `shopping_offline_${user_id}_${groupId}_${courseId}`;
    setCookie(cookieName, '', -1);
    cookieName = `shopping_offline_summery_${user_id}_${courseId}`;
    setCookie(cookieName, '', -1);
  });

  $(document).on('click', '#dsply-more', function () {
    setStyle($('.dt-course'), 'display: block;');
    setStyle($('.dt-course-sub-header'), 'display: block;');
    setStyle($('.courses_list > dt'), 'display: block;');
    $(this).hide();
  });

  $(document).on('click', '#right-button', function () {
    setVisible($('#left-button'));
    $('#outer').animate({
      scrollLeft: $('#outer').scrollLeft() - 200
    }, 800);
  });

  $(document).on('click', '#left-button', function () {
    $('#outer').animate({
      scrollLeft: $('#outer').scrollLeft() + 200
    }, 800);/*, function() {
        if ($('#outer').scrollLeft() <= 0) {
        setInvisible($('#left-button'));
        }
        });*/
  });

  $(document).on('click', '.saveAndClose', function (e) {
    groupId = $(this).data('groupid');
    dataEvent = $(this).data('event');
    current = $(this).parent().parent().attr('id');
    frameIndex = Number(current.split('_')[1]);
    var validation = isValidation(dataEvent, 'saveAndClose');
    if (validation) {
      switch (current) {
        case 'organization_3': {
          saveGroupDetails(groupId);
          if (groupsObj[groupId]['payment']) ajaxSaveGroupDetails(groupId);
          break;
        }
        case 'organization_4': {
          if (dataEvent == 'update-students'){
            saveStudentsDetails(groupId, true);
            if (isPayment(groupId)){
              ajaxSaveStudentsDetails(groupId);
            }
          }
          else if (dataEvent == 'save-students'){
            saveStudentsDetails(groupId);
            frameIndex++;
            createChildrenList(groupId, false);
            setAdaptedCourses(groupId);
            courseInit(groupId, true, 'adapted');
            setGroupName(groupId);
            setEnableIfSelectedCourse(groupId);
            current = '#organization_5';
            $(`${current} #title_icons, ${current} #edit_dtl`).hide();
            $(`${current} .prev`).addClass(`group-${groupId}`);
          }
          break;
        }
        case 'organization_5': {
          // delCookie(`shopping_offline_${user_id}_${groupId}_`);
          saveCoursesDetails(groupId);
          break;
        }
      }
      setExistGroup(groupId);
      $(this).parent().parent().hide();
    }
    else {
      switch (dataEvent) {
        // case 'groups': {
        //   setBorder($('.organization-name input'), 'red');
        //   break;
        // }
        case 'save-group-detail': {
          groupName = $('#group-name');
          gender = $('#gender input[type="radio"]');
          ages = $('.ages input[type="radio"]');
          if (groupName.val().length == 0) setBorder(groupName, 'red');
          if (!gender.is(':checked')) 
            for(item of gender){
              setBorder($(item), 'red !important;')
            }
          // gender.forEach((item) => {setBorder(item, 'red')});
          if (!ages.is(':checked')) 
          for(item of ages){
            setBorder($(item), 'red !important;');
          }
          // ages.forEach((item) => {setBorder(item, 'red')});
          break;
        }
      }
    }
   
    if (validation) {
      $(current).hide();
      $('.background-opacity').hide();
      $('#organization_2').show();
      $('.page-id-124203 .ast-container').css('background', '#FAFAFA');
      reset();
    }
  });

  function saveOrganizationDetails(groupId) {
    groupsObj[groupId]['organizationName'] = $('.organization-name input').val();
    groupsObj[groupId]['organizationSymbol'] = $('.organization-symbol input').val();
    groupsObj[groupId]['city'] = $('.city input').val();
  }

  function saveGroupDetails(groupId) {
    if (isPayment(groupId)) groupsObj[groupId]['prevGroupName'] = groupsObj[groupId]['groupName'];
    groupName = $('#group-name').val();
    gender = $('#gender input[type="radio"]:checked').val();
    ages = $('.ages input[type="radio"]:checked').val();
    groupsObj[groupId]['groupName'] = groupName;
    groupsObj[groupId]['gender'] = gender;
    groupsObj[groupId]['ages'] = ages;
    saveGeneralDetailsInCookie(groupId);
  }

  function saveStudentsDetails(groupId, isPayment = false) {
    childTable.rows().every(function () {
      var childId = $(this.data()[0]).attr("data-id");
      childName = $(childTable.cell(this.index(), 1).node()).find('input').val();
      lastName = $(childTable.cell(this.index(), 2).node()).find('input').val();
      id = $(childTable.cell(this.index(), 3).node()).find('input').val();
      emptyRowValues = ['','שדה חובה'];
      if (!emptyRowValues.includes(childName) && !emptyRowValues.includes(lastName) && !emptyRowValues.includes(id)){
        groupsObj[groupId]['students'][childId] = {};
        groupsObj[groupId]['students'][childId]['childName'] = childName;
        groupsObj[groupId]['students'][childId]['lastName'] = lastName;
        groupsObj[groupId]['students'][childId]['id'] = id;
        groupsObj[groupId]['students'][childId]['isPayment'] = false;
        if(!groupsObj[groupId]['courses']['adapted'][childId]) {
          initChildCourses(groupId, childId);
        }
      }
    });
    // saveStudentsDetailsInCookie(groupId, isPayment);
  }

  function saveCoursesDetails(groupId) {
    var checkedCourses = getCheckedCourses(groupId);
    var choiceType = getChoiceType();
    if (choiceType == 'all') {
      groupsObj[groupId]['courses']['all'] = checkedCourses;
      setAllCoursesForChildren(groupId);
    }
    else if (choiceType == 'adapted') {
      groupsObj[groupId]['courses']['adapted'][$('.child_now').data('id')]['private'] = checkedCourses;
    }
    removeCoursesFromPrivate(groupId);
  }

  function saveSelectedCoursesInCookie(groupId) {
    coursesTotal = createCoursesTotal(groupId);
    for (const key in coursesTotal) {
      count = coursesTotal[key]['count'];
      names = coursesTotal[key]['names'].join(', ');
      cookieValue = { 'id': key, 'groupId': groupId, 'count': count, 'names': names };
      cookieName = `shopping_offline_${user_id}_${groupId}_${key}`;
      setCookie(cookieName, JSON.stringify(cookieValue), 1);
    }
  }

  function groupInit(groupId) {
    $('#group-name').val( groupsObj[groupId]['groupName']);
    $('#' +  groupsObj[groupId]['gender']).prop('checked', true);
    $('#' +  groupsObj[groupId]['ages']).prop('checked', true);
  }

  function studentInit(groupId) {
    rows = [];
    i = 1;
    students = groupsObj[groupId]['students'];
    for (const key in students) {
      rows.push(addRow(key, String(i++), students[key]['childName'], students[key]['lastName'], students[key]['id']));
    }
    childTable.rows.add(rows).draw();
  }

  function courseInit(groupId, isPayment, option) {
    courses = groupsObj[groupId]['courses'][option];
    if (isPayment)
      paidCourses = groupsObj[groupId]['paidCourses'][option];
    if (option == 'all') {
      isPayment ? setCoursesChecked('all', courses, getAllPaidCourses(groupId), isPayment) : setCoursesChecked('all', courses);
      $('#adapted').removeClass('active');
      $('#all').addClass('active');
      setStyle($('.child_lst'), 'display: none;');
    }
    else {
      isPayment ? setCoursesChecked('adapted', courses['private'], paidCourses, isPayment) : setCoursesChecked('adapted', courses);
      $('#all').removeClass('active');
      $('#adapted').addClass('active');
      setStyle($('.child_lst'), 'display: inline-block;');
    }
  }
	
  function setExistGroup(groupId) {
    genderText = '';
    switch (groupsObj[groupId]['gender']) {
      case 'boys': genderText = 'תלמידים'; break;
      case 'girls': genderText = 'תלמידות'; break;
      case 'no_division': genderText = 'תלמידים/ות'; break;
    }
    payment = groupsObj[groupId]['payment'] ? 'פעיל' : 'ממתין לתשלום';
    count = Object.keys(groupsObj[groupId]['students']).length;
    $(`.existGroup[data-groupid=${groupId}] .existGroupName`).text(groupsObj[groupId]['groupName']);
    $(`.existGroup[data-groupid=${groupId}] .existGroupCount`).text(`${count} ${genderText}`);
    $(`.existGroup[data-groupid=${groupId}] .payment`).text(payment);
  }

  function setGroupName(groupId) {
    groupName = groupsObj[groupId]['groupName'];
    $('#organization_4 .header span').html(groupName);
    $('#organization_5 .header span').html(groupName);
  }

  $(document).on('click', '#organization_6 .pay-and-downloads', function (event) {
    debugger
    event.preventDefault();
    groupId = $(this).data('groupid');
    saveOrganizationDetailsInCookie();
    for (const groupId of groupList) {
      if (groupId == 0) continue; //delete
      // deleteNewStudentsFlag(groupId);
      saveSelectedCoursesInCookie(groupId);
      saveCoursesDetailsInCookie(groupId);
    }
    debugger
    window.location.href = $(this).find('a').attr('href');
  });

  function saveOrganizationDetailsInCookie() {
    organizationName = groupsObj[0]['organizationName'];
    organizationSymbol = groupsObj[0]['organizationSymbol'];
    city = groupsObj[0]['city'];
    cookieName = `offline_organization_details_${user_id}`;
    cookieValue = {
      'user_name': user_name, 'last_name': last_name, 'user_email': user_email,
      'organizationName': organizationName, 'organizationSymbol': organizationSymbol,
      'city': city, 'total': total, 'groupList': groupList
    }
    setCookie(cookieName, JSON.stringify(cookieValue), 1);
  }

  function saveGeneralDetailsInCookie(groupId) {
    groupName = groupsObj[groupId]['groupName'];
    gender = groupsObj[groupId]['gender'];
    ages = groupsObj[groupId]['ages'];
    payment = groupsObj[groupId]['payment'];
    cookieName = `offline_group_general_details_${user_id}_${groupId}`;
    cookieValue = {
      'groupName': groupName, 'gender': gender, 'ages': ages, 'payment': payment
    }
    setCookie(cookieName, JSON.stringify(cookieValue), 1);
  }

  function saveStudentsDetailsInCookie(groupId) {
    var students = groupsObj[groupId]['students'];
    cookieName = `offline_group_students_details_${user_id}_${groupId}`;
    cookieValue = {
      'students': students
    }
    setCookie(cookieName, JSON.stringify(cookieValue), 1);
  }

  function saveCoursesDetailsInCookie(groupId) {
    debugger
    allCourses = groupsObj[groupId]['courses']['all'];
    adaptedCourses = groupsObj[groupId]['courses']['adapted'];
    cookieValue = {
      'allCourses': allCourses, 'adaptedCourses': adaptedCourses
    }
    if (isPayment(groupId)){
      paidAllCourses = groupsObj[groupId]['paidCourses']['all'];
      paidAllCourses = [...allCourses, ...paidAllCourses];
      for (const key in groupsObj[groupId]['students']){
        if(!groupsObj[groupId]['paidCourses']['adapted'][key])
          initChildCourses(groupId, key, 'paidCourses');
        paidAdaptedCourses = groupsObj[groupId]['paidCourses']['adapted'];
        paidAdaptedCourses[key]['all'] = adaptedCourses[key]['all'].concat(paidAdaptedCourses[key]['all']);
        paidAdaptedCourses[key]['private'] = adaptedCourses[key]['private'].concat(paidAdaptedCourses[key]['private']);
      }
      cookieValue = {
        'allCourses': allCourses, 'adaptedCourses': adaptedCourses, 'paidAllCourses': paidAllCourses, 'paidAdaptedCourses': paidAdaptedCourses
      }
    }
    cookieName = `offline_group_courses_details_${user_id}_${groupId}`;
    setCookie(cookieName, JSON.stringify(cookieValue), 1);
  }

  function setVisible(elem) {
    elem.css('visibility', 'visible');
  }

  function setInvisible(elem) {
    elem.css('visibility', 'hidden');
  };

  function setBorder(elem, color) {
    color == 'none' ? elem.css('border', color) : elem.css('border', `1px solid ${color}`);
  };

  function setDisable(elem) {
    setStyle(elem, 'cursor: not-allowed;');
    elem.prop('disabled', true);
  }

  function setNonDisable(elem) {
    setStyle(elem, 'cursor: pointer;')
    elem.prop('disabled', false);
  }

  function setStyle(elem, style) {
    elem.attr('style', style);
  }

  function setOpacity(elem, val) {
    elem.css('opacity', val);
  }

  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function delCookie(cname) {
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.includes(cname)) {
        setCookie(c, '', -1);
      }
    }
  }

  function ajaxSaveGroupDetails(groupId){
    var data = {
      "action" : "save_general_details",
      "userId" : user_id,
      "groupId" : groupId,
      "groupName" : groupsObj[groupId]['groupName'], 
      "prevGroupName" : groupsObj[groupId]['prevGroupName'], 
      "generalDetails" : {'groupName': groupsObj[groupId]['groupName'], 'ages': groupsObj[groupId]['ages'], 'gender': groupsObj[groupId]['gender']}
    };
    jQuery.post("/wp-admin/admin-ajax.php", data, function(response){
      ;
    });
  }

  function ajaxSaveStudentsDetails(groupId){
    var data = {
      "action" : "save_students_details",
      "userId" : user_id,
      "groupId" : groupId,
      "groupName" : groupsObj[groupId]['groupName'], 
      "students" : groupsObj[groupId]['students']
    };
    jQuery.post("/wp-admin/admin-ajax.php", data, function(response){
    });
  }

  function disablePaidCourses(courseId){
    setOpacity($('.dt-course div[data-id="' + courseId +'"]'), '0.5');
    $('label[for="cbx-' + courseId + '"]').css('pointer-events','none');
  }

  function enableCourses(){
    $('#organization_5 input[type=checkbox]').prop("checked", false);
    setOpacity($('.dt-course div'), '1');
    $('.cbx').css('pointer-events','');
  }

  function isPayment(groupId){
    // debugger
    return groupsObj[groupId]['payment'];
    // return (typeof(groupsObj[groupId]) != 'undefined' && groupsObj[groupId]['payment']);
  }

  function alertNoCoursesSelected(groupId){
    if (!isPayment(groupId) && groupsObj[groupId]['courses']['all'].length == 0){
      var studentsWithNoCourses = getStudentsListWithNoCourses(groupId);
      studentsName = studentsWithNoCourses.map((key) => groupsObj[groupId]['students'][key]['childName'] + ' ' + groupsObj[groupId]['students'][key]['lastName']);
      if (studentsWithNoCourses.length){
        alert(`שים לב שלא נבחרו קורסים לכל התלמידים:
        ${studentsName}`);
      }
    }
  }

  function getStudentsListWithCourses(groupId){
    studentsWithCourses = Object.keys(groupsObj[groupId]['courses']['adapted']);
    studentsWithCourses = studentsWithCourses.filter((key) => groupsObj[groupId]['courses']['adapted'][key]['private'].length);
    // studentsWithCourses = studentsWithCourses.filter((key) => groupsObj[groupId]['courses']['adapted'][key][0]['private'].length);
    return studentsWithCourses;
  }

  function getStudentsListWithNoCourses(groupId){
    allStudents = Object.keys(groupsObj[groupId]['students']);
    studentsWithCourses = getStudentsListWithCourses(groupId);
    var studentsWithNoCourses = allStudents.filter((key) => !studentsWithCourses.includes(key));
    return studentsWithNoCourses;
  }

  function getChoiceType(){
   return $($('.course_header div[class="active"]').get(0)).attr('id');
  }

  function incrementChildId(childId){
    [existingGroupId, existingId] = childId.split('_');
    return `${existingGroupId}_${parseInt(existingId)+1}`;
  }

  function removeEmptyStudents(groupId){
    studentsObject = groupsObj[groupId]['students'];
    for (var key in studentsObject) {
      if (Object.values(studentsObject[key]).length == 0)
        delete groupsObj[groupId]['students'][key];
    }
  }

  function uploadImage(cls){
    var formData = new FormData(jQuery(`.${cls}`)[0]);
    formData.append('action','concepta_save_user_file');
    formData.append('user_id', user_id);
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType : 'text',
      success: function(data) {
      },
      error: function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    });
  }

  function setDisableSelectedCourses(groupId){
    allCourses = groupsObj[groupId]['courses']['all'];
    propChecked('all', allCourses, true);
  }

  function initChildCourses(groupId, childId, index = 'courses'){
    groupsObj[groupId][index]['adapted'][childId] = {};
    groupsObj[groupId][index]['adapted'][childId]['all'] = [];
    groupsObj[groupId][index]['adapted'][childId]['private'] = [];
  }

  function setAllCoursesForChildren(groupId){
    let checkedCourses = getCheckedCourses(groupId);
    for (const key in groupsObj[groupId]['students']){
      if (!Object.keys(groupsObj[groupId]['courses']['adapted']).length || !groupsObj[groupId]['courses']['adapted'].hasOwnProperty(key))
        initChildCourses(groupId, key)
      groupsObj[groupId]['courses']['adapted'][key]['all'] = checkedCourses;
    }
  }

  function getAllPaidCourses(groupId){
    var allPaidCourses = [];
    var paidCourses = groupsObj[groupId]['paidCourses']['adapted'];
    for (const key in paidCourses){
      allPaidCourses = allPaidCourses.concat(paidCourses[key]['all'], paidCourses[key]['private']);
    }
    allPaidCourses = Array.from(new Set(allPaidCourses));
    return allPaidCourses;
  }

  function removeCoursesFromPrivate(groupId){
    var adaptedCourses = groupsObj[groupId]['courses']['adapted'];
    for (const key in adaptedCourses){
      adaptedCourses[key]['private'] = adaptedCourses[key]['private'].filter((id) => !groupsObj[groupId]['courses']['all'].includes(id));
    }
  }

  function setDisabledPaidStudents(groupId){
    for(const key in groupsObj[groupId]['students']){
      if (!groupsObj[groupId]['students'][key].hasOwnProperty('isPayment')){
        $(`.td_id[value=${groupsObj[groupId]['students'][key]['id']}]`).siblings('.icon-delete').hide();
        setDisable($(`.td_id[value=${groupsObj[groupId]['students'][key]['id']}]`));
      }
    }
  }

  function initChildId(groupId){
    lastId = '';
    try {
      lastId = Object.keys(groupsObj[groupId]['students']).pop().split('_')[1];
    }
    catch {
      lastId = '-1';
    }
    return `${groupId}_${parseInt(lastId)+1}`;
  }

  function deleteNewStudentsFlag(groupId){
    for(const key in groupsObj[groupId]['students']){
      delete groupsObj[groupId]['students'][key].isPayment;
    }
    saveStudentsDetailsInCookie(groupId);
  }

  function toDisplayRightButton(){
    size = 0;
    for(i = 0; i < $('.child').length; i++) {
      size += $($('.child')[i]).outerWidth();
    }
    return size > jQuery('#outer').width();
  }

  function deleteCookies() {
    const cookies = document.cookie.split(';');
    cookies.forEach(function(cookie){
      const cookieName = cookie.split('=')[0].trim();              
      if (cookieName.includes('shopping_offline_') || cookieName.includes('offline_organization_details_') ||
          cookieName.includes('offline_group_')) {
        setCookie(cookieName, '', -1);
      }
    });
  }

});
