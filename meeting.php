<div class="loadingoverlay"></div>
<div class="modal fade" id="Modal_Meeting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="m-gb-1">
      <div class="m-gb-2">
        <div class="m-gb-3">
          <div class="modal-content">
            <div class="modal-heade p-0">
              <h5 class="modal-title" id="">Request an Online Meeting
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
              </h5>
            </div>
            <div class="modal-body ps-0 pe-0 pb-0">
                <div class="row">
                  <input type="hidden" name="" class="exp_cm" id="exp_cm" value="<?=$exp_id;?>">
                  <input type="hidden" name="" class="exl_cm" id="exl_cm" value="<?=$exl_id;?>">
                  <div class="col-12 box-modal-meeting-left">
                    <div class="box-l">
                      <div class="lable_start_date">
                        Select Date :
                      </div>
                      <div class="">
                        <div id="datepicker-Meeting"></div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-4 Slot-box">
                            <label class="form-label mt-1">Avialable Slot :</label>
                        </div>
                        <div class="col-4 col-sm-3 col-md-3">
                          <select class="selectpicker" data-width="80px" id="h_time" data-size="6">
                            <?php for ($i=9; $i < 23 ; $i++) { ?>
                              <option><?php echo sprintf("%'02d\n",$i); ?></option>
                            <?php   } ?>
                          </select>

                        </div>
                        <div class="col-2 col-sm-1">
                          <label class="form-label mt-1"> : </label>
                        </div>
                        <div class="col-4 col-sm-3 col-md-3">
                          <select class="selectpicker" data-width="80px" id="m_time" data-size="6">
                            <option>00</option>
                            <option>15</option>
                            <option>30</option>
                            <option>45</option>
                          </select>
                        </div>
                        <div class="col-2 col-sm-1 p-0">
                          <div class="mt-1">

                            <span class="d-inline-block" tabindex="0" id="tooltip_tz" data-toggle="tooltip" title="">
                              <img  src="<?=ROOTPATHDOMAIN?>assets/images/m-question.png" class="m-question" />
                            </span>

                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-12 text-center">
                          <button type="button" name="button" class="btn  btn-Appointment">Add An Appointment Option</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 box-modal-meeting-right">

                    <div class="box-r">

                      <div class="box-list">
                        <!-- <div class="make-add-box mt-2">
                          <div class="make-add-box-1">
                            Online Meeting Option 1
                          </div>
                          <div class="make-add-box-2">
                            August 8, 2022 <i class="bi bi-clock"></i><label for="">13:00</label>
                          </div>
                          <div class="make-add-box-3">
                            Eastern Daylight Time (GMT-4)
                          </div>
                          <div class="make-add-box-4">
                            Singapore Time (GMT+8) : August 8,2022 <i class="bi bi-clock"></i><label for=""> 14:00
                          </div>
                        </div> -->
                      </div>


                      <div class="mt-3 box-fix-bottom">
                        <div class="make-add-message">
                          Message
                          <input type="text" class="form-control" id="message_mit" placeholder="Write Message..." value="">
                        </div>
                        <div class="box-btn-save">
                          <button type="button" name="button" class="btn btn-save-app">Make An Appointment</button>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>




            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?=ROOTPATHDOMAIN?>assets/js/moment/moment.min.js" ></script>
<script src="<?=ROOTPATHDOMAIN?>assets/js/moment-timezone-develop/builds/moment-timezone-with-data.js" ></script>

<script>
$(document).ready(function() {
  var endDate = new Date();
  endDate.setDate(endDate.getDate() + 3);
  $('#datepicker-Meeting').datepicker({
    language: "en",
    startDate: "+3d",
    todayHighlight: true
  });
  $('#datepicker-Meeting').datepicker('update', endDate);

  // $("#datepicker-Meeting").datepicker("option", "minDate", -0);
  // $("#datepicker-Meeting").datepicker("option", "maxDate", endDate);

  $('.table-condensed .prev').html('<i class="bi bi-chevron-left"></i>');
  $('.table-condensed .next').html('<i class="bi bi-chevron-right"></i>');

  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

  get_timez();

});

function get_timez(){
  var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  var offset = new Date().getTimezoneOffset()/60;
  var city = timezone.split("/")[1];

  var offset_val = (offset*-1);
  offset_val = offset_val < 0 ? offset_val : '+' + offset_val;
  $('#tooltip_tz').attr('data-bs-title', city+' Time. Offset UTC '+offset_val+':00 hours ').tooltip();
}

function getTimeZone(d) {
    return /\((.*)\)/.exec(new Date(d).toString())[1];
}

$('body').on('click','.btn-Appointment',function(){

  var datetime_list = $('.datetime-list').map(function(){
    return $(this).val();
  }).get();

  var dfull = '';
  var dfull_n = '';

  var h_time = $('#h_time').val();
  var m_time = $('#m_time').val();
  var h_time_n = '';
  var m_time_n = '';

  var datetime = '';
  var timez = '';
  var zGMT = '';

  var jsDate = $('#datepicker-Meeting').datepicker('getDate');
  var offset_val = '';
  if (jsDate !== null) { // if any date selected in datepicker
      jsDate instanceof Date; // -> true
      var d = jsDate.getDate();
      var m = jsDate.getMonth();
      var y = jsDate.getFullYear();
      var options = { year: 'numeric', month: 'long', day: 'numeric' };
      dfull = jsDate.toLocaleDateString('en-US', options);
      var oldDate = new Date(y+'-'+(m + 1)+'-'+d+' '+h_time+':'+m_time);
      timez = getTimeZone(oldDate);

      var hour = oldDate.getHours();
      var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
      var offset = new Date().getTimezoneOffset()/60;
      var city = timezone.split("/")[1];

      var newDate = oldDate;
      // newDate = new Date(newDate);
      // console.log(((offset*-1) - 7));
      newDate.setHours(hour + ((offset*-1) - 7));
      var n = oldDate.getTimezoneOffset();
      zGMT = n / -60;
      dfull_n = newDate.toLocaleDateString('en-US', options);
      h_time_n = newDate.getHours();
      m_time_n = newDate.getMinutes();
      h_time_n = h_time_n > 9 ? h_time_n : '0' + h_time_n;
      m_time_n = m_time_n > 9 ? m_time_n : '0' + m_time_n;
      // console.log(dfull_n);
      datetime = y+'-'+(m + 1)+'-'+d+' '+h_time+':'+m_time+':00';

      zGMT = zGMT < 0 ? zGMT : '+' + zGMT;
      offset_val = (offset*-1);
      offset_val = offset_val < 0 ? offset_val : '+' + offset_val;
  }

  if($.inArray(datetime, datetime_list) !== -1){
    swal({
      type: 'error',
      title:'',
      text: 'ไม่สามารถเพิ่มเวลาที่เหมือนกันได้'
    })
    return false;
  }else {
    $('#Modal_Meeting .modal-dialog').addClass('modal-xl');
    $('.box-modal-meeting-left').removeClass('col-lg-12');
    $('.box-modal-meeting-left').addClass('col-12 col-sm-12 col-md-12 col-lg-6');
    $('.box-list .make-add-box').length;
    if($('.box-list .make-add-box').length < 3){
      var index = $('.box-list .make-add-box').length;
      $('.box-modal-meeting-right .box-r .box-list').append('<div class="make-add-box index-list-'+index+' mt-2" index="'+index+'">\
            <div class="make-add-box-1">Online Meeting Option '+(index + 1)+'<i class="bi bi-dash-circle-fill btn-minus-list" index="'+index+'"></i></div>\
            <div class="make-add-box-2">'+dfull+' <i class="bi bi-clock"></i><label for="">'+h_time+':'+m_time+'</label></div>\
            <div class="make-add-box-3">Time Zone (GMT+7)</div>\
            <div class="make-add-box-4">'+city+' (GMT'+offset_val+') : '+dfull_n+' <i class="bi bi-clock"></i><label for=""> '+h_time_n+':'+m_time_n+'</div>\
            <input type="hidden" class="datetime-list" name="" value="'+datetime+'">\
            <input type="hidden" class="timezone-list" name="" value="'+timezone+'">\
            <input type="hidden" class="gmt-list" name="" value="'+(offset*-1)+'">\
          </div>');
    }else {
      swal({
        type: 'error',
        title:'',
        text: 'ไม่สามารถเพิ่มเวลาเกิน 3 เวลาได้'
      })
      return false;
    }

  }


  $('.box-modal-meeting-right').show();
});

$('body').on('click','.btn-minus-list',function(){
  var index = $(this).attr('index');
  $('.index-list-'+index).remove();
  if($('.box-list .make-add-box').length == 0){
    $('#Modal_Meeting .modal-dialog').removeClass('modal-xl');
    $('.box-modal-meeting-left').removeClass('col-lg-6');
    $('.box-modal-meeting-left').addClass('col-lg-12');
    $('.box-modal-meeting-right').hide();
  }else {
    var n_index = 0;
    $('.make-add-box').each(function(){
      var index_list = $(this).attr('index');
      $(this).removeClass('index-list-'+index_list);
      $(this).addClass('index-list-'+n_index);
      $(this).attr('index',n_index);
      $(this).find('.make-add-box-1').html('Online Meeting Option '+(n_index + 1)+'<i class="bi bi-dash-circle-fill btn-minus-list" index="'+n_index+'"></i>');
      n_index++;
    });
  }
});

$('body').on('click','.box-btn-save',function(){
  var datetime_list = $('.datetime-list').map(function(){
    return $(this).val();
  }).get();
  var timezone_list = $('.timezone-list').map(function(){
    return $(this).val();
  }).get();
  var gmt_list = $('.gmt-list').map(function(){
    return $(this).val();
  }).get();
  var message_mit = $('#message_mit').val();
  var exp_cm = $('#exp_cm').val();
  var exl_cm = $('#exl_cm').val();

  swal({
    title: 'Save ?',
    text: "Make An Appointment ?",
    type: 'warning',
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Agree',
    cancelButtonText: 'Cancel'
  },function(isConfirm){
    if(isConfirm){

        $('.loadingoverlay').show();

        setTimeout(function () {
          $.ajax({
              url: '/meeting_call.php',
              type: 'POST',
              async: false,
              dataType : 'json',
              data: {'wscall':'save-meeting','datetime_list':datetime_list,'timezone_list':timezone_list,'gmt_list':gmt_list,'message_mit':message_mit,'exp_cm':exp_cm,'exl_cm':exl_cm},
              success: function(res) {
                // console.log(res);
                if(res.res_code == "00"){
                  $('.loadingoverlay').hide();
                  swal({
                    title: 'Success',
                    text: "Make An Appointment Success",
                    type: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Agree'
                  },function(isConfirm){
                    if(isConfirm){
                      window.location.reload();
                    }
                  });

                }else if (res.res_code == "02") {
                  $('.loadingoverlay').hide();
                  swal({
                    type: 'error',
                    title:'',
                    text: 'ไม่พบอีเมล หรือ รูปแบบอีเมลของท่านไม่ถูกต้อง'
                  })
                }else {
                  $('.loadingoverlay').hide();
                  swal({
                    type: 'error',
                    title:'',
                    text: 'เกิดข้อผิดพลาดกรุณาลองใหม่ภายหลัง'
                  })
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
              }
          });
        }, 200);
    }
  });




  // console.log(datetime_list);
});

</script>


<style media="screen">
.tooltip-inner {
    min-width: 100px;
    max-width: 100%;
}
#Modal_Meeting .modal-header ,#Modal_Meeting .modal-footer{
  border: none;
}
#Modal_Meeting h5{
  border-bottom: 1px solid #b2b2b2;
  font-weight: 600;
  padding-bottom: 10px;
  color: #378dd7;
}
#Modal_Meeting .modal-content{
  padding: 25px;
  border-radius: 30px;
  background-color: unset;
}
#Modal_Meeting  .modal-dialog{
background: #ffffff;
border-radius: 30px;
}
#Modal_Meeting .modal-header{
  color: #378dd7;
  padding-bottom: 0;
  margin-bottom: 15px;
}
.lable_start_date{
  font-size: 20px;
  font-weight: bold;
}
.datepicker-inline , #datepicker-Meeting table{
  width: 100%;
}
.table-condensed .prev i, .table-condensed .next i ,.table-condensed .prev i:hover, .table-condensed .next i:hover{
  border-radius: 50px;
  background: #378dd7;
  color: #fff;
  width: 30px;
  height: 30px;
  display: inline-block;
}
.table-condensed .next i{
  padding-left: 2px;
  padding-top: 2px;
}
.table-condensed .prev i{
  padding-right: 4px;
  padding-top: 1px;
  margin-left: 1px;
}
#datepicker-Meeting{
  border-radius: 8px;
  border: solid 1px #b2b2b2;
  padding: 15px 5px;
  background: #fff;
}
.tooltip-inner {
  border-radius: 10px;
   background-image: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);
}

.tooltip-arrow{
  --bs-tooltip-bg:#3186d3 ;
}


.datepicker-days table tr td.active.active {
  border-image-source: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);
  border-image-slice: 1;
  background-image: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);
}
.m-gb-1{
  background-image: url(/assets/images/m-gb-1.png);
  background-repeat: no-repeat;
    background-size: contain;
}
.m-gb-2{
  background-image: url(/assets/images/m-gb-2.png);
  background-repeat: no-repeat;
  background-position: right;
}
.m-gb-3{
  background-image: url(/assets/images/m-gb-3.png);
  background-repeat: no-repeat;
  background-position: left bottom;
}
.m-question{
  width: 20px;
  cursor: pointer;
}
.Slot-box{
  font-weight: bold;
}

#Modal_Meeting .bootstrap-select button{
border: 1px solid #b2b2b2;
border: 1px solid #b2b2b2;
height: 40px;
background: #fff;
}
#Modal_Meeting .bootstrap-select .dropdown-toggle::after{
  /* border-top: .3em solid #b2b2b2; */
}
#Modal_Meeting .bootstrap-select .filter-option-inner-inner{
  color: #378dd7!important;
  font-size: 18px;
  position: absolute;
  top: 7px;
  font-weight: bold;
}

.btn-Appointment ,.btn-Appointment:hover,.btn-Appointment:active,.btn-Appointment:focus{
  background-color: #378dd7;
  color: #ffffff;
  width: 90%;
}

#Modal_Meeting .bootstrap-select .dropdown-item.active, #Modal_Meeting .bootstrap-select .dropdown-item:active{
  color: #378dd7;
  background-color: unset;
}
.make-add-box{
  padding: 10px 15px;
  border-radius: 10px;
  border: solid 1px #b2b2b2;
  background-color: #fff;
  font-weight: bold;

}

.make-add-box-1{
  color:#378dd7;
}
.make-add-box-2{
  color: #2f2f2f;
}
.make-add-box-2 i{
  padding-left: 10px;
  font-size: 16px;
  padding-right: 5px;
}
.make-add-box-3{
  color: #666666;
  font-size: 16px;
}

.make-add-box-4 i{
  padding-left: 10px;
  font-size: 16px;
  padding-right: 5px;
}
.make-add-box-4{
  color: #378dd7;
  font-size: 16px;
}

.make-add-message{
  padding: 10px 15px;
   border-radius: 10px;
  background-color: #f0f0f0;
  color: #378dd7;
  font-weight: bold;

}

.make-add-message input{
  border: none;
  background: none;
  border-bottom: 1px solid #378dd7;
  border-radius: 0;
  padding: 0;
  color: #378dd7;
  font-weight: normal;
  font-size: 16px;
}
.make-add-message input::placeholder {
  color: #378dd7;

}
.box-modal-meeting-right{
  display: none;
  border-left: 1px solid #bbafaf;
}

.box-r{
  position: relative;
  height: 100%;
}
.box-list{
  padding-bottom: 155px;
}
.box-fix-bottom{
  position: absolute;
  width: 100%;
  bottom: 0;
}
.box-btn-save{
  padding: 20px 50px 10px 50px;
}
.btn-save-app,.btn-save-app:hover,.btn-save-app:focus,.btn-save-app:active{
  color: #fff;
  background: #378dd7;
  width: 100%;
}
.btn-minus-list{
  float: right;
  cursor: pointer;
}

@media (max-width: 1199px) {
  .Slot-box{
    font-size: 16px;
    padding-right: 0px;
  }
}

@media (max-width: 991px) {
  .box-modal-meeting-right{
    border-left: 0px solid #bbafaf;
  }
}
@media (max-width: 425px) {
  .box-btn-save{
    padding: 20px 0px 10px 0px;
  }
  .btn-Appointment{
    width: 100%;
  }
}
</style>
