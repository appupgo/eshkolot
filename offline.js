jQuery(document).ready(function ($) {
    var numChildren = childrenObj.length;
    var title = ['עריכת פרטים כללים', ('פרטי ילדים - משפחת ' + last_name + ' ' + year), 'רכישת קורסים ומסלולים למשפחת' + ' ' + last_name + ' ' + year];
    var total = 0;
    var childTable = $('#child_table').DataTable({
      rowReorder: true, rowReorder: { selector: '.drag-rows' },
      language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/he.json' },
      columns: [
        { title: 'מס.' },
        { title: 'שם פרטי' },
        { title: 'טווח גיל' },
        { title: 'סיסמא - תעודת זהות' }],
    });
  
    $(document).on('click', "#next_edit_details", function (event) {
      if (isValidation(frameIndex)) {
        switch(frameIndex){
          case 1:{
            $('#num_children').css('border', 'none');
            break;
          }
        }
        setNonDisable($('#next_edit_details'))
        eval('nextEditDetails' + String(frameIndex))();
        generalEditDetails();
        frameIndex++;
      }
      else {
        setDisable($('#next_edit_details'));
        switch (frameIndex){
          case 1:{
            if($('.agree input[type=checkbox]').is(":checked"))
                $('#num_children').css('border', '1px solid red');
            else
                $('.agree').css('border', '1px solid red');
            break;
          }
        }
      }
    });
      
      initDetails();
  
  //   $(document).on('click', ".icon_cls", function (event) {
  //     frameIndex = $(this).data('index');
  //     generalEditDetails();
  //   });
  
    function generalEditDetails(prev = false) {
      $('.present_icon').removeClass("present_icon");
      $('#edit_details_' + frameIndex).hide();
      if (prev) {
        $('#edit_details_' + (frameIndex - 1)).show();
        $('#title_set').html(title[frameIndex - 2]);
        $($('.icon_cls')[frameIndex - 2]).addClass("present_icon");
      }
      else {
        $('#edit_details_' + (frameIndex + 1)).show();
        $('#title_set').html(title[frameIndex]);
        $($('.icon_cls')[frameIndex]).addClass("present_icon");
        if (!isValidation(frameIndex)) {
          setDisable($('#next_edit_details'));
        }
      }
    };
  
    function nextEditDetails1(prev = false) {
      $('.closeBtn').hide();
        if (isEdit){
          initChildTable();
        }
        else {
            createChildTable(Number($('#num_children').val()) - Number(numChildren));
        }
      numChildren = $('#num_children').val();
      $('#previous_edit_details').attr('style', 'display: inline-block;');
      setDisable($("#next_edit_details"));
    };
  
    function nextEditDetails2(prev = false) {
      var allChild = $('.child_name');
      childTable.rows().every(function () {
        var childId = $(this.data()[0]).attr("data-id");
        childName = $(childTable.cell(this.index(), 1).node()).find('input').val();
        age = $(childTable.cell(this.index(), 2).node()).find('select').val();
        id = $(childTable.cell(this.index(), 3).node()).find('input').val();
        childrenObj[childId]['childName'] = childName;
        childrenObj[childId]['age'] = age;
        childrenObj[childId]['id'] = id;
        childrenObj[childId]['courses'] = [];
        if(childrenObj[childId]['paidCourses'] == undefined) {
          childrenObj[childId]['paidCourses'] = [];
          childrenObj[childId]['is_saved'] = false;
        }
      });
      $('.child_lst span').remove();
      createChildrenList(childrenObj);
      $('#adapted').removeClass('active');
      $('#all').addClass('active');
      propChecked('all', courseAllChildren);
      $('.child_lst').attr('style', 'display: none;');
      if (!prev) {
        $("#next_edit_details").html("לסל הקניות");
      }
      setDisable($("#next_edit_details"));
    };
  
    function nextEditDetails3(prev = false) {
      saveSelectedCourses();
      $("#icons, #title_icons, #edit_dtl, #forward-back-btn, #previous_edit_details, #title_set").hide();
      $('.close-cart').show();
      $('.page-id-124145 #content').css('background', '#FAFAFA');
      shoppingCart();
    };
  
    $(document).on('click', "#previous_edit_details", function (event) {
      generalEditDetails(true);
      frameIndex--;
      if (frameIndex <= 1) {
        $('#previous_edit_details').attr('style', 'display: none;');
        $('.closeBtn').show();
      }
      else {
        if (frameIndex == 2) {
          saveSelectedCourses();
        }
        $("#next_edit_details").html("המשך ←");
        $("#next_edit_details").css('background-color', '#2D2828');
        $("#next_edit_details").css('color', '#FFF');
        eval('nextEditDetails' + String(frameIndex))(true);
      }
    });
  
    function createChildTable(num) {
      rows = [];
      rowsCount = childTable.rows().count();
      if (num > 0) {
        for (var i = 1; i <= num; i++) {
          rows.push(addRow(String(childId), String(rowsCount + i)));
          childrenObj[childId] = {};
          childId++;
        }
        childTable.rows.add(rows);
      }
      else if (num < 0) {
        for (var i = 1; i <= Math.abs(num); i++) {
          childId = $(childTable.row(':last').data()[0]).attr("data-id");
          delete childrenObj[childId];
          childTable.row(':last').remove();
        }
      }
      childTable.draw();
    }
  
    function initChildTable() {
      childTable.clear().draw();
      for(i = 0; i < childrenObj.length; i++){
        if (childrenObj[i] != undefined) {
          childTable.row.add(addRow(String(i), String(i + 1), childrenObj[i]['childName'], childrenObj[i]['id'])).draw();
          $($(".age_selected option[value=" + childrenObj[i]['age'] + "]")[i]).prop("selected", true);
          if (childrenObj[i]['is_saved']) {
            $(`.td_id[value=${childrenObj[i]['id']}]`).siblings('.icon-delete').hide();
            setDisable($(`.td_id[value=${childrenObj[i]['id']}]`));
          }
        }
      }
      setNonDisable($('#next_edit_details'));
    }
  
    function addRow(childId, num, name = '', id = '') {
      return ['<td data-id="' + childId + '"><p class="auto_inc">' + num + '.</p></td>',
      '<td><div class="name-container"><input class="child_name" value="' + name + '" required></div></td>',
        '<td><select class="age_selected"><option disabled selected value/><option value="10-16">10-16</option><option value="16-25">16-25</option><option value="25-35">25-35</option><option value="35-45">35-45</option><option value="45+">45+</option></select></td>',
      '<td><div class="id-container"><input class="td_id" type="text" value="' + id + '" maxlength="9" required><svg class="icon-delete" xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 20 17" fill="none"><path d="M18.5017 8.5C18.5017 12.8916 14.7227 16.5 9.99985 16.5C5.27703 16.5 1.49805 12.8916 1.49805 8.5C1.49805 4.10836 5.27703 0.5 9.99985 0.5C14.7227 0.5 18.5017 4.10836 18.5017 8.5Z" stroke="#2D2828"/><line x1="5.80273" y1="8.18945" x2="14.6045" y2="8.18945" stroke="black"/></svg><div class="drag-rows"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="17" viewBox="0 0 10 17" fill="none"><ellipse cx="2.6715" cy="1.7385" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="2.6715" cy="8.50022" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="2.6715" cy="15.2619" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="1.7385" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="8.50022" rx="1.8004" ry="1.7385" fill="#2D2828"/><ellipse cx="8.06993" cy="15.2619" rx="1.8004" ry="1.7385" fill="#2D2828"/></svg></div></div></td>'];
    }
  
    $(document).on('click', '.icon-delete', function () {
      numChildren = childTable.rows().count();
      if (numChildren <= 1) {
        $(this).attr("disabled", true);
        $(this).css('cursor', 'not-allowed');
      }
      else {
        childId = $(childTable.row($(this).parents('tr')).data()[0]).attr("data-id");
        delete childrenObj[childId];
        childTable.row($(this).parents('tr')).remove().draw();
        childTable.rows().every(function (rowIdx) {
          prevChildId = $(this.cell(rowIdx, 0).data()).attr("data-id");
          this.cell(rowIdx, 0).data('<td data-id="' + String(prevChildId) + '"><p class="auto_inc">' + String(rowIdx + 1) + '.</p></td>');
        });
        numChildren--;
        $('#num_children').val(String(numChildren));
      }
    });
  
    $(document).on('mouseover', '.icon-delete', function () {
      if (childTable.rows().count() <= 1) {
        $(this).attr("disabled", true);
        $(this).css('cursor', 'not-allowed');
      }
    });
  
  
    $(document).on('click', '.add_child', function () {
      var rowsCount = childTable.rows().count();
      numChildren++;
      childTable.row.add(addRow(String(childId), String(rowsCount + 1))).draw();
      childrenObj[childId] = {};
      childId++;
      $('#child_table .icon-delete').attr("disabled", false);
      $('#child_table .icon-delete').css('cursor', 'pointer');
      $('#num_children').val(String(numChildren));
    });
  
  
    $(document).on('change', '#excelUpload', function (e) {
      childTable.rows().every(function () {
        childId = $(this.data()[0]).attr("data-id");
        delete childrenObj[childId];
      });
      childTable.clear();
      readXlsxFile(this.files[0]).then(function (rows) {
        rows.shift();
        numChildren = rows.length;
        $('#num_children').val(String(numChildren));
        for (let i = 0; i < rows.length; i++) {
          childTable.row.add(addRow(String(childId), String(i + 1), rows[i][0], rows[i][2])).draw();
          $($(".age_selected option[value=" + rows[i][1] + "]")[i]).prop("selected", true);
          childrenObj[childId] = {};
          childId++;
        }
      });
      setNonDisable($('#next_edit_details'));
    });
  
    function createChildrenList(data) {
      for (const key in data) {
        $('.child_lst #outer').append('<span class="child" data-id="' + String(key) + '">' + data[key]['childName'] + '</span>');
      }
      $('.child_lst  #outer span:first-child').addClass('child_now');
      if (toDisplayRightButton()) {
        setVisible($('#right-button'));
      }
    }
  
    $(document).on('click', '#all', function () {
      coursePerChild[String($('.child_now').data('id'))] = getCheckedCourses('adapted');
      $('.child_now').removeClass('child_now');
      propChecked('all', courseAllChildren);
      $('#adapted').removeClass('active');
      $(this).addClass('active');
      $('.child_lst').attr('style', 'display: none;');
      $('.courses_list').attr('style', 'margin-top: 30px;');
    });
  
    $(document).on('click', '#adapted', function () {
      $('.child_now').removeClass('child_now');
      $('.child_lst span:first-child').addClass('child_now');
      courseAllChildren = getCheckedCourses('all');
      propChecked('adapted', coursePerChild[String($('.child_now').data('id'))]);
      $('#all').removeClass('active');
      $(this).addClass('active');
      $('.child_lst').attr('style', 'display: inline-block;');
      $('.courses_list').attr('style', 'margin-top: 0px;');
      if (toDisplayRightButton()) {
        setVisible($('#right-button'));
      }
    });
  
    $(document).on('click', '.child', function () {
      coursePerChild[String($('.child_now').data('id'))] = getCheckedCourses('adapted');
      $('.child_now').removeClass('child_now');
      $(this).addClass('child_now');
      propChecked('adapted', coursePerChild[String($('.child_now').data('id'))]);
    });
  
    function getCheckedCourses(type) {
      checked = $('.courses_list input[type=checkbox]:checked');
      checkedIds = jQuery.map(jQuery.makeArray(checked), function(val) {  
        return $(val).data('id');  
      });
      if (isEdit || type == 'adapted')
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
  
    function propChecked(type, checkedCourseArr = []) {
      enableCourses();
      for (var i = 0; i < checkedCourseArr.length; i++) {
        $('.courses_list input[type=checkbox][data-id=' + checkedCourseArr[i] + ']').prop("checked", true);
      }
      if (type == 'adapted'){
        for (var i = 0; i < courseAllChildren.length; i++) {
          $('.courses_list input[type=checkbox][data-id=' + courseAllChildren[i] + ']').prop("checked", true);
          $('.dt-course div[data-id="' + courseAllChildren[i] +'"]').css('opacity', '0.5');
          $('label[for="cbx-' + courseAllChildren[i] + '"]').css('pointer-events','none');
        }
      }
      if (isEdit) {
        if(type == 'all'){
          for (var i = 0; i < childrenObj.length; i++) {
            if(typeof(childrenObj[i]['paidCourses']) != 'undefined') {
              performPaidCoursesChecked(childrenObj[i]['paidCourses'], true);
            }          
          }
        }
        else {
          if(typeof(childrenObj[String($('.child_now').data('id'))]['paidCourses']) != 'undefined') {
            performPaidCoursesChecked(childrenObj[String($('.child_now').data('id'))]['paidCourses'], true);
          }
        } 
      }
    }
  
    function saveSelectedCourses() {
      if (getChoiceType() == 'all') {
        courseAllChildren = getCheckedCourses('all');
      }
      else {
        coursePerChild[String($('.child_now').data('id'))] = getCheckedCourses('adapted');
      }
    }
  
    $(document).on('click', '.courses_list svg', function () {
      courseElements = $(this).parent('dt').next('dl');
      if ($(this).parent('dt').text().includes('קורסים')){
        courseElements = $(this).parent('dt').nextUntil('.dt-course-header');
      }
      if (courseElements.is(':visible')){
        courseElements.hide();
        $(this).css('transform', 'rotate(90deg)');
      }
      else{
        courseElements.show();
        $(this).css('transform', 'rotate(0deg)');
      }
    });
  
    $(document).on('click', '.courses_list input[type=checkbox]', function () {
      if (getCheckedCourses('all').length || getCheckedCourses('adapted').length || courseAllChildren.length || isSelectedCourseForChild()){
        $('#lbl_added').fadeIn("slow");
        setNonDisable($('#next_edit_details'));
      }
      else {
        $('#lbl_added').fadeOut("slow");
        setDisable($('#next_edit_details'));
      }
    });
  
    function shoppingCart() {
      count = Object.keys(childrenObj).length;
      forWhom = Object.values(childrenObj).map(x => ` ${x['childName']}`);
      for (let i = 0; i < courseAllChildren.length; i++) {
        createShoppingCartTable(courseAllChildren[i], forWhom, count);
      }
      totalCourses = {};
      for (const key in coursePerChild) {
        for (let i = 0; i < coursePerChild[key].length; i++) {
          if (!totalCourses[coursePerChild[key][i]]) {
            totalCourses[coursePerChild[key][i]] = {};
            totalCourses[coursePerChild[key][i]]['count'] = 0;
            totalCourses[coursePerChild[key][i]]['childName'] = [];
          }
          totalCourses[coursePerChild[key][i]]['count']++;
          totalCourses[coursePerChild[key][i]]['childName'].push(childrenObj[key]['childName']);
        }
      }
      for (const key in totalCourses) {
        count = totalCourses[key]['childName'].length;
        forWhom = totalCourses[key]['childName'].join(', ');
        createShoppingCartTable(key, forWhom, count);
      }
      $('#total-price').html(total + '&nbsp;₪');
    }
  
    function createShoppingCartTable(id, forWhom, count) {
      deleteCookies();
      course = $('.courses_list div[data-id="' + id + '"]');
      courseName = course.data('name');
      coursePrice = course.data('price');
      totalPrice = Number(coursePrice) * count;
      total += totalPrice;
      icon = course.data('icon');
      courseNameWithIcon = icon ? courseName + icon : courseName;
      $('#table_course tbody').append('<tr><td>' + courseNameWithIcon + '</td><td>' + count + '</td><td><div style="overflow-y:scroll; height:40px">' + forWhom + '</div></td><td class="td-price">' + totalPrice + '&nbsp;₪</td><td><svg class="btnDelete" data-id="' + id + '" xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none"><circle cx="18.0185" cy="18.0194" r="12.241" transform="rotate(-45.0624 18.0185 18.0194)" stroke="#2D2828"/><line x1="14.3629" y1="13.2632" x2="23.1816" y2="22.0627" stroke="black"/><line x1="13.6656" y1="22.0746" x2="22.4651" y2="13.2559" stroke="black"/></svg></td><tr>');
      cookieValue = { 'id': id, 'courseName': courseName, 'count': count };
      cookieName = `shopping_offline_${user_id}_${id}`;
      setCookie(cookieName, JSON.stringify(cookieValue), 1);
    }
  
    $(document).on('click', '.btnDelete', function () {
      price = Number($(this).parent().siblings('.td-price').html().split('&nbsp;')[0]);
      total -= price;
      $('#total-price').html(total + '&nbsp;₪');
      $(this).closest('tr').remove();
      courseId = $(this).data('id');
      cookieName = `shopping_offline_${user_id}_${courseId}`;
      setCookie(cookieName, '', -1);
    });
  
    $(document).on('click', '#dsply-more', function () {
      $('.dt-course, .dt-course-sub-header, .courses_list > dt').attr('style', 'display: block;');
      $('.courses_list').css('height', '300px');
      $(this).hide();
    });
  
    function isTableValid(arr){
      warnText = ['שדה חובה', 'נא להזין ת"ז תקינה', 'אין להזין ערכים כפולים'];
      ids = [];
      count = 0;
      valid = 0;
      for(let i = 0; i < arr.length; i++){
        fname = $(arr[i]).find('.child_name');
        age = $(arr[i]).find('.age_selected');
        id = $(arr[i]).find('.td_id');
  
        if( count >= 3 && count % 3 == 0 && ((fname.val() == '' && age.val() == null && id.val() == '') || (fname.val() == warnText[0] && age.val() == warnText[0] && id.val() == warnText[0]))){
          valid = 0;
          continue;
        }
  
        if (['', warnText[0]].includes(fname.val())) {
          valid++;
          fname.val(warnText[0]).css('color', 'red').css('font-style', 'italic');
        }
        else {
          count++;
        }
  
        if ([null, warnText[0]].includes(age.val())) {
          valid++;
          age.css('border', '1px solid red');
        }
        else {
          count++;
        }
  
        if (['', warnText[0]].includes(id.val())) {
          valid++;
          id.val(warnText[0]).css('color', 'red').css('font-style', 'italic');
        }
        else if (!idValidation(id.val())) {
          valid++;
          id.val(warnText[1]).css('color', 'red').css('font-style', 'italic');
        }
        else if (ids.includes(id.val())) {
          valid++;
          id.val(warnText[2]).css('color', 'red').css('font-style', 'italic');
        }
        else {
          ids.push(id.val());
          count++;
        }
      }
      return valid == 0;
    }
  
    $(document).on('focus', '.child_name, .age_selected, .td_id', function () {
      setNonDisable($('#next_edit_details'));
      $(this).css('border', 'none');
      if (['שדה חובה', 'נא להזין ת"ז תקינה', 'אין להזין ערכים כפולים'].indexOf($(this).val()) !== -1) {
        $(this).val('').css('color', '#2D2828').css('font-style', 'normal');
      }
    });
  
    function isValidation(frameIndex) {
      var validation = true;
        queryIndex = getQueryIndex();
        if ( queryIndex > 1 && frameIndex == 1 && queryIndex > frameIndex) {
            return validation;
        }
      switch (frameIndex) {
        case 1: {
          validation = ($('.agree input[type=checkbox]').is(":checked")) && Number($('#num_children').val()); 
          break;
        }
        case 2: {
          validation = isTableValid($('#child_table tbody tr'));
          break;
        }
        case 3: {
          validation = $('#edit_details_3 input[type=checkbox]:checked').length > 0;
          break;
        }
      }
      return validation;
    }
  
    function idValidation(id) {
      id = String(id).trim();
      if (id.length > 9 || isNaN(id)) return false;
      id = id.length < 9 ? ("00000000" + id).slice(-9) : id;
      return Array.from(id, Number).reduce((counter, digit, i) => {
        const step = digit * ((i % 2) + 1);
        return counter + (step > 9 ? step - 9 : step);
      }) % 10 === 0;
    }
  
    $(document).on('click', '.agree input[type=checkbox]', function () {
      $(this).is(":checked") ? setNonDisable($('#next_edit_details')) : setDisable($('#next_edit_details'));
    });
  
    function setDisable(elem) {
        if ($(elem).attr("id") === "next_edit_details")
            setNonDisable(elem);
        else {
          setStyle(elem, 'cursor: not-allowed;');
          elem.css('opacity', '0.5');
          elem.prop('disabled', true);
        }
    }
  
    function setNonDisable(elem) {
      setStyle(elem, 'cursor: pointer;')
      elem.css('opacity', '1');
      elem.prop('disabled', false);
    }
  
    function setStyle(elem, style) {
      elem.attr('style', style);
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
  
    function toDisplayRightButton() {
      size = 0;
      for (i = 0; i < $('.child').length; i++) {
        size += $($('.child')[i]).outerWidth();
      }
      return size > jQuery('#outer').width();
    }
  
    function setVisible(elem) {
      elem.css('visibility', 'visible');
    }
  
    function setInvisible(elem) {
      elem.css('visibility', 'hidden');
    };
  
    $(document).on('click', "#edit_details_4 .pay-and-downloads", function (event) {
      cookieName = `offline_private_details_${user_id}`;
      city = $(city_children).val();
      courses = setCourses();
      cookieValue = {
        'user_name': user_name, 'last_name': last_name, 'user_email': user_email,
        'city': city, 'children': childrenObj, 'courses': courses, 'total': total
      }
      setCookie(cookieName, JSON.stringify(cookieValue), 1);
    });
  
    $(document).on('click', ".close-cart .shopping-back", function (event) {
    });
  
    $(document).on('click', ".close-cart .shopping-close", function (event) {
      $(this).hide();//remove???
    });
  
    function isSelectedCourseForChild(){
      for(const key in coursePerChild) {
        if (coursePerChild[key].length)
          return true;
      }
      return false;
    }
  
    function setCourses(){
      finalCourses = {};
      for(const key in childrenObj) {
        finalCourses[key] = {};
        finalCourses[key]['all'] = courseAllChildren;
        finalCourses[key]['private'] = coursePerChild[key];
        paidCourses =  (childrenObj[key]['paidCourses'] != undefined) ? childrenObj[key]['paidCourses'] : [];
        paidCourses = paidCourses.concat(courseAllChildren).concat(coursePerChild[key]);
        childrenObj[key]['paidCourses'] = [...new Set(paidCourses)];
      }
      return finalCourses;
    }
  
    function performPaidCoursesChecked(courses, toDisable){
      for (var i = 0; i < courses.length; i++) {
        $('.courses_list input[type=checkbox][data-id=' + courses[i] + ']').prop("checked", true);
        if (toDisable) disablePaidCourses(courses[i]);
      }
    }
  
    function disablePaidCourses(courseId){
      $('.dt-course div[data-id="' + courseId +'"]').css('opacity', '0.5');
      $('label[for="cbx-' + courseId + '"]').css('pointer-events','none');
    }
  
    function enableCourses(){
      $('.courses_list input[type=checkbox]').prop("checked", false);
      $('.dt-course div').css('opacity', '1');
      $('.cbx').css('pointer-events','');
    }
  
    function getChoiceType(){
     return $($('.course_header div[class="active"]').get(0)).attr('id');
    }
      
      function initDetails(){
          index = getQueryIndex();
          if (typeof index !== 'undefined' && index !== null && (index == 2 || index == 3)){
              for(let i = 1; i < index; i++){
                  frameIndex = i;
                  jQuery('#next_edit_details').click();
              }
          }
      }
      
      function getQueryIndex(){
          const queryString = window.location.search;
          const urlParams = new URLSearchParams(queryString);
          const index = urlParams.get('index');
          return index;
      }

      function deleteCookies() {
        const cookies = document.cookie.split(';');
        cookies.forEach(function(cookie) {
          const cookieName = cookie.split('=')[0].trim();              
          if (cookieName.includes('shopping_offline_') || cookieName.includes('offline_private_details_')) {
            setCookie(cookieName, '', -1);
          }
        });
      }
});
