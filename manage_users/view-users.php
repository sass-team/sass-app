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
      <h1>Tutors</h1>
   </div> <!-- #content-header -->


   <div id="content-container">

   <div class="row">

      <div class="col-md-12">

         <div class="portlet">

            <div class="portlet-header">

               <h3>
                  <i class="fa fa-filter"></i>
                  Column Filtering
               </h3>

            </div> <!-- /.portlet-header -->

            <div class="portlet-content">

               <div class="table-responsive">

                  <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper" role="grid"><div class="row dt-rt"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><table class="table table-striped table-bordered table-hover dataTable-helper dataTable datatable-columnfilter" data-provide="datatable" data-info="true" id="DataTables_Table_1" aria-describedby="DataTables_Table_1_info">
                        <thead>
                        <tr role="row"><th data-filterable="true" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Rendering engine" style="width: 174px;">Rendering engine</th><th data-direction="asc" data-filterable="true" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Browser" style="width: 257px;">Browser</th><th data-filterable="true" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Platform(s)" style="width: 243px;">Platform(s)</th><th data-filterable="false" class="hidden-xs hidden-sm sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version" style="width: 149px;">Engine version</th><th data-filterable="false" class="hidden-xs hidden-sm sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" style="width: 103px;">CSS grade</th></tr>
                        <tr cls="dataTable-filter-row"><th class=""><input type="text" class="form-control input-sm show" placeholder="Rendering engine"></th><th class=""><input type="text" class="form-control input-sm show" placeholder="Browser"></th><th class=""><input type="text" class="form-control input-sm show" placeholder="Platform(s)"></th><th class="hidden-xs hidden-sm"><input type="text" class="form-control input-sm hide" placeholder="Engine version"></th><th class="hidden-xs hidden-sm"><input type="text" class="form-control input-sm hide" placeholder="CSS grade"></th></tr></thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all"><tr class="odd">
                           <td class=" ">Trident</td>
                           <td class=" sorting_1">AOL browser (AOL desktop)</td>
                           <td class=" ">Win XP</td>
                           <td class="hidden-xs hidden-sm ">6</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Gecko</td>
                           <td class=" sorting_1">Camino 1.0</td>
                           <td class=" ">OSX.2+</td>
                           <td class="hidden-xs hidden-sm ">1.8</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Gecko</td>
                           <td class=" sorting_1">Camino 1.5</td>
                           <td class=" ">OSX.3+</td>
                           <td class="hidden-xs hidden-sm ">1.8</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Gecko</td>
                           <td class=" sorting_1">Firefox 2.0</td>
                           <td class=" ">Win 98+ / OSX.2+</td>
                           <td class="hidden-xs hidden-sm ">1.8</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Gecko</td>
                           <td class=" sorting_1">Firefox 3.0</td>
                           <td class=" ">Win 2k+ / OSX.3+</td>
                           <td class="hidden-xs hidden-sm ">1.9</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Trident</td>
                           <td class=" sorting_1">Internet
                              Explorer 5.0
                           </td>
                           <td class=" ">Win 95+</td>
                           <td class="hidden-xs hidden-sm ">5</td>
                           <td class="hidden-xs hidden-sm ">C</td>
                        </tr><tr class="odd">
                           <td class=" ">Trident</td>
                           <td class=" sorting_1">Internet
                              Explorer 5.5
                           </td>
                           <td class=" ">Win 95+</td>
                           <td class="hidden-xs hidden-sm ">5.5</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Trident</td>
                           <td class=" sorting_1">Internet
                              Explorer 6
                           </td>
                           <td class=" ">Win 98+</td>
                           <td class="hidden-xs hidden-sm ">6</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Trident</td>
                           <td class=" sorting_1">Internet Explorer 7</td>
                           <td class=" ">Win XP SP2+</td>
                           <td class="hidden-xs hidden-sm ">7</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">KHTML</td>
                           <td class=" sorting_1">Konqureror 3.1</td>
                           <td class=" ">KDE 3.1</td>
                           <td class="hidden-xs hidden-sm ">3.1</td>
                           <td class="hidden-xs hidden-sm ">C</td>
                        </tr><tr class="odd">
                           <td class=" ">KHTML</td>
                           <td class=" sorting_1">Konqureror 3.3</td>
                           <td class=" ">KDE 3.3</td>
                           <td class="hidden-xs hidden-sm ">3.3</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Gecko</td>
                           <td class=" sorting_1">Netscape 7.2</td>
                           <td class=" ">Win 95+ / Mac OS 8.6-9.2</td>
                           <td class="hidden-xs hidden-sm ">1.7</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Presto</td>
                           <td class=" sorting_1">Nintendo DS browser</td>
                           <td class=" ">Nintendo DS</td>
                           <td class="hidden-xs hidden-sm ">8.5</td>
                           <td class="hidden-xs hidden-sm ">C/A</td>
                        </tr><tr class="even">
                           <td class=" ">Presto</td>
                           <td class=" sorting_1">Nokia N800</td>
                           <td class=" ">N800</td>
                           <td class="hidden-xs hidden-sm ">-</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Webkit</td>
                           <td class=" sorting_1">Safari 1.2</td>
                           <td class=" ">OSX.3</td>
                           <td class="hidden-xs hidden-sm ">125.5</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Webkit</td>
                           <td class=" sorting_1">Safari 1.3</td>
                           <td class=" ">OSX.3</td>
                           <td class="hidden-xs hidden-sm ">312.8</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="odd">
                           <td class=" ">Webkit</td>
                           <td class=" sorting_1">Safari 2.0</td>
                           <td class=" ">OSX.4+</td>
                           <td class="hidden-xs hidden-sm ">419.3</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr><tr class="even">
                           <td class=" ">Webkit</td>
                           <td class=" sorting_1">Safari 3.0</td>
                           <td class=" ">OSX.4+</td>
                           <td class="hidden-xs hidden-sm ">522.1</td>
                           <td class="hidden-xs hidden-sm ">A</td>
                        </tr></tbody></table><div class="row dt-rb"><div class="col-sm-6"><div class="dataTables_info" id="DataTables_Table_1_info">Showing 1 to 18 of 18 entries</div></div><div class="col-sm-6"></div></div></div>
               </div> <!-- /.table-responsive -->


            </div> <!-- /.portlet-content -->

         </div> <!-- /.portlet -->



      </div> <!-- /.col -->

   </div> <!-- /.row -->


   </div> <!-- /#content-container -->



   </div>

<?php include ROOT_PATH . "inc/view/footer.php"; ?>