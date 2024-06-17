<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<?php
global $user_email;
?>
<link href="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/css/icon" rel="stylesheet">
<link id="pagestyle" href="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/css/material-dashboard.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
  .tabcontent {
    display: none
  }

  .headingone {
    font-size: 38px;
  }

  .tab {
    display: flex;
    flex-direction: column;
    width: 15%;
    float: left;
    background: #1f262a;
    height: 100vh;
    box-shadow: 0 4px 7px -1px rgb(0 0 0 / 11%), 0 2px 4px -1px rgb(0 0 0 / 7%);
    align-items: unset;
    justify-content: flex-start;
    position: relative;
    bottom: 0;
  }

  .justify-content-space-between {
    justify-content: space-between
  }

  .tab_div {
    display: flex;
    justify-content: space-between;
  }

  .tab_div2 {
    border-radius: 5px;
    background: transparent;
    box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
    padding: 30px 0px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    width: 20%;
    border: 2px dashed #f0f0f1;
  }

  .mode {
    color: #fff;
    font-size: 19px;
    margin: 0;
    font-weight: bold;
    width: 100%;
    text-align: center;
  }

  .mode .Live {
    color: #3F0;
  }

  .mode .Test {
    color: #f4a535;
  }

  .Balance {
    font-size: 15px;
    margin: 0px;
    text-align: center;
    margin-top: 17px;
    width: 100%;
    border-top: 0px;
    padding-top: 17px;
    color: #fff;
  }

  .Balance span {
    display: block;
    font-size: 39px;
    color: #35f49b;
    font-weight: bold;
    margin-top: -12px;
  }

  #wpfooter {
    display: none !important
  }
</style>

<body class="g-sidenav-show  bg-gray-200" id="adivaha-body">
  <main class="main-content position-relative   ps ps--active-y">
    <nav class="navbar-main navbar-expand-lg bg-gradient-dark ps bg-white shadow-none sidenav-header" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3 mt-4 h-100">
        <div class="row">
          <div class="col-sm-6">
            <div class="tab_div">
              <div class="tab_div1"> <img src="https://www.adivaha.com/images/logo.png" style="max-width: 100%;width: 170px;">
                <nav class="h-100 mt-6">
                  <p class="ms-1 mb-0 font-weight-bold text-white headingone">Wordpress adivaha&reg; Plugin</p>
                  <p class="ms-1  mb-0 heading2">WordPress Travel Plugin and White label Travel Solutions to the travel agencies.</p>
                </nav>
              </div>
            </div>
          </div>
          <div class="col-sm-6"><img src="../wp-content/plugins/adiaha-hotel/images/rightsideimg.png" style="max-width: 100%;
    margin-top: -70px;"></div>

        </div>

      </div>
    </nav>
    <div class="tab">
      <button class="tablinks btn bg-gradient-primary" id="defaultOpen" onClick="openCity(event, 'api-setting')">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center"> <i class="material-icons opacity-10">dashboard</i> </div>
        <span class="nav-link-text ms-1">API Settings</span>
      </button>
      <button class="tablinks btn bg-gradient-primary" onClick="openCity(event, 'api-settings')">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center"> <i class="material-icons opacity-10">connecting_airports</i> </div>
        <span class="nav-link-text ms-1">GDS Solutions</span>
      </button>
    </div>
    <div class=" py-4 tabcontent py-4" id="api-setting">
      <form method="POST" id="general-settings">
        <div class="row">
          <div class="col-xl-12 col-sm-6 mb-xl-0 mb-4">
            <p class="heading_genral py-1">GENERAL SETTINGS
              </h1>
          </div>
          <div class="col-xl-12 col-sm-6 mb-xl-0 mb-4">
            <div class="px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Your Email Address</h6>
                            <p class="text-xs text-secondary mb-0">Enter your Email Address</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="input-group input-group-outline <?php echo ($user_email != "") ? 'is-filled' : '' ?>">
                          <label class="form-label">Your Email Address</label>
                          <input type="text" class="form-control" name="user_email" id="user_email" value="<?php echo ($user_email) ? $user_email : '' ?>" required="true" onFocus="focused(this)" onfocusout="defocused(this)">
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-sm-6 mb-xl-0 mb-4">
            <ul class="navbar-nav  justify-content-start  py-1" style="flex-direction:row;float:right">
              <input type="hidden" name="action" value="updateEmail">
              <li class="nav-item d-flex">
                <input type="submit" class="btn bg-gradient-primary mb-0 customebtn" name="updateEmail" id="updateEmail" value="Update Email">
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>

    <div class=" py-0 tabcontent" id="api-settings" style="width: 85%;margin: 0px;float: right;">
      <iframe src="https://www.adivaha.com/GDS-API-Integration.html" style="width:100%;height:100vh"></iframe>

    </div>

    </div>
  </main>
  <script src="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/js/bootstrap.min.js"></script>
  <script src="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/js/perfect-scrollbar.min.js"></script>
  <script src="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/js/smooth-scrollbar.min.js"></script>
  <script src="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/js/buttons.js"></script>
  <script src="<?php echo ADIVAHA__PLUGIN_URL; ?>/asset/js/material-dashboard.min.js"></script>
  <script>
    function openCity(evt, cityName) {
      // Declare all variables
      var i, tabcontent, tablinks;

      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      // Show the current tab, and add an "active" class to the button that opened the tab
      document.getElementById(cityName).style.display = "block";
      evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
  </script>
  <script>
    fns();

    function fns() {
      jQuery('#updateEmail').click(function(e) {
        e.preventDefault();

        swal({
            title: "Are you sure?",
            text: "Really, Do you want to update your email address?",
            icon: "warning",
            buttons: {
              'cancel': 'No',
              'danger': 'Yes'
            },
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var frm_data = jQuery('#general-settings').serialize();
              jQuery.ajax({
                type: 'POST',
                url: '<?php echo ADIVAHA__PLUGIN_SITE_URL ?>/wp-admin/admin-ajax.php',
                data: frm_data,
                success: function(data) {
                  if (data == 1) {
                    swal("Good job!", "Your email address has been changed!", "success").then((value) => {
                      location.reload();
                    });
                  } else {
                    swal("Sorry!!", "Try again later.").then((value) => {
                      location.reload();
                    });
                  }
                },
                error: function(errorThrown) {
                  swal("Sorry!", "Some Error Occured. Contact to the Developer Team.", "error").then((value) => {
                    location.reload();
                  });
                }
              });
            } else {
              swal("Yipee!", "Your email address remains unchanged.").then((value) => {
                location.reload();
              });
            }
          });
      });
    }


    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/616a912f86aee40a5736dbfe/1fi44e8ra';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);

      window.Tawk_API.onPrechatSubmit = function(data) {
        //place your code here
        console.log(data[0].answer);
        var my_data = {
          gtouch_name: data[0].answer,
          email: data[1].answer,
          action_from: "chat",
          your_isd: $("#gtouch_isd").val(),
          your_phone: data[2].answer,
          your_message: "Free Plugin Online Chat - " + data[3].answer,
          action: "my_register"
        };

        $.ajax({
          type: "post",
          data: my_data,
          url: "https://www.adivaha.com/custom_ajax.php",
          crossDomain: true,
          success: function(data) {
            alert("Saved");
            // window.location.href = "https://www.adivaha.com/thanks.html?d=" + email + "&p=" + gtouch_name;
          }
        });
      };

      window.Tawk_API.onOfflineSubmit = function(data) {
        //place your code here
        console.log(data);
        //var data = JSON.stringify(data);
        var questions = data.questions;
        var my_data = {
          gtouch_name: questions[0].answer,
          email: questions[1].answer,
          action_from: "chat",
          your_isd: $("#gtouch_isd").val(),
          your_phone: questions[2].answer,
          your_message: "Free Plugin Offline Chat - " + questions[3].answer,
          action: "my_register"
        };

        $.ajax({
          type: "post",
          data: my_data,
          url: "https://www.adivaha.com/custom_ajax.php",
          crossDomain: true,
          success: function(data) {
            alert("Saved");
            // window.location.href = "https://www.adivaha.com/thanks.html?d=" + email + "&p=" + gtouch_name;
          }
        });
      };
    })();

    /*var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/616a912f86aee40a5736dbfe/1fi44e8ra';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();*/
  </script>