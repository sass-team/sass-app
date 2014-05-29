<?php
require '../inc/init.php';
$general->logged_out_protect();

$page_title = "My Account - Profile";
$section = "manage_users";
require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';
?>


   <div id="content">

   <div id="content-header">
      <h1>Tables Advanced</h1>
   </div>
   <!-- #content-header -->


   <div id="content-container">

   <div class="row">

   <div class="col-md-12">

   <div class="portlet">

   <div class="portlet-header">

      <h3>
         <i class="fa fa-table"></i>
         Kitchen Sink
      </h3>

   </div>
   <!-- /.portlet-header -->

   <div class="portlet-content">

   <div class="table-responsive">

   <table
      class="table table-striped table-bordered table-hover table-highlight table-checkable"
      data-provide="datatable"
      data-display-rows="10"
      data-info="true"
      data-search="true"
      data-length-change="true"
      data-paginate="true"
      >
   <thead>
   <tr>
      <th class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </th>
      <th data-filterable="true" data-sortable="true" data-direction="desc">Rendering engine</th>
      <th data-direction="asc" data-filterable="true" data-sortable="true">Browser</th>
      <th data-filterable="true" data-sortable="true">Platform(s)</th>
      <th data-filterable="false" class="hidden-xs hidden-sm">Engine version</th>
      <th data-filterable="true" class="hidden-xs hidden-sm">CSS grade</th>
   </tr>
   </thead>
   <tbody>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Trident</td>
      <td>Internet
         Explorer 5.0
      </td>
      <td>Win 95+</td>
      <td class="hidden-xs hidden-sm">5</td>
      <td class="hidden-xs hidden-sm">C</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Trident</td>
      <td>Internet
         Explorer 5.5
      </td>
      <td>Win 95+</td>
      <td class="hidden-xs hidden-sm">5.5</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Trident</td>
      <td>Internet
         Explorer 6
      </td>
      <td>Win 98+</td>
      <td class="hidden-xs hidden-sm">6</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Trident</td>
      <td>Internet Explorer 7</td>
      <td>Win XP SP2+</td>
      <td class="hidden-xs hidden-sm">7</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Trident</td>
      <td>AOL browser (AOL desktop)</td>
      <td>Win XP</td>
      <td class="hidden-xs hidden-sm">6</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Misc</td>
      <td>NetFront 3.1</td>
      <td>Embedded devices</td>
      <td class="hidden-xs hidden-sm">-</td>
      <td class="hidden-xs hidden-sm">C</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Misc</td>
      <td>NetFront 3.4</td>
      <td>Embedded devices</td>
      <td class="hidden-xs hidden-sm">-</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Gecko</td>
      <td>Firefox 2.0</td>
      <td>Win 98+ / OSX.2+</td>
      <td class="hidden-xs hidden-sm">1.8</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Gecko</td>
      <td>Firefox 3.0</td>
      <td>Win 2k+ / OSX.3+</td>
      <td class="hidden-xs hidden-sm">1.9</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Gecko</td>
      <td>Camino 1.0</td>
      <td>OSX.2+</td>
      <td class="hidden-xs hidden-sm">1.8</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Gecko</td>
      <td>Camino 1.5</td>
      <td>OSX.3+</td>
      <td class="hidden-xs hidden-sm">1.8</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Gecko</td>
      <td>Netscape 7.2</td>
      <td>Win 95+ / Mac OS 8.6-9.2</td>
      <td class="hidden-xs hidden-sm">1.7</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Presto</td>
      <td>Nokia N800</td>
      <td>N800</td>
      <td class="hidden-xs hidden-sm">-</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Presto</td>
      <td>Nintendo DS browser</td>
      <td>Nintendo DS</td>
      <td class="hidden-xs hidden-sm">8.5</td>
      <td class="hidden-xs hidden-sm">C/A</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>KHTML</td>
      <td>Konqureror 3.1</td>
      <td>KDE 3.1</td>
      <td class="hidden-xs hidden-sm">3.1</td>
      <td class="hidden-xs hidden-sm">C</td>
   </tr>
   <tr>
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>KHTML</td>
      <td>Konqureror 3.3</td>
      <td>KDE 3.3</td>
      <td class="hidden-xs hidden-sm">3.3</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Webkit</td>
      <td>Safari 1.2</td>
      <td>OSX.3</td>
      <td class="hidden-xs hidden-sm">125.5</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Webkit</td>
      <td>Safari 1.3</td>
      <td>OSX.3</td>
      <td class="hidden-xs hidden-sm">312.8</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Webkit</td>
      <td>Safari 2.0</td>
      <td>OSX.4+</td>
      <td class="hidden-xs hidden-sm">419.3</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   <tr class="">
      <td class="checkbox-column">
         <input type="checkbox" class="icheck-input">
      </td>
      <td>Webkit</td>
      <td>Safari 3.0</td>
      <td>OSX.4+</td>
      <td class="hidden-xs hidden-sm">522.1</td>
      <td class="hidden-xs hidden-sm">A</td>
   </tr>
   </tbody>
   </table>
   </div>
   <!-- /.table-responsive -->


   </div>
   <!-- /.portlet-content -->

   </div>
   <!-- /.portlet -->


   </div>
   <!-- /.col -->

   </div>
   <!-- /.row -->


   </div>
   <!-- /#content-container -->


   </div>
   <!-- /.col -->

   </div>
   <!-- /.wrapper -->


<?php include ROOT_PATH . "inc/view/footer.php"; ?>