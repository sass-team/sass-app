<?php
require 'app/init.php';
$general->logged_out_protect();

// viewers
$page_title = "Dashboard - SASS Management System";
$section = "dashboard";
require ROOT_PATH . 'app/views/header.php';
require ROOT_PATH . 'app/views/sidebar.php';

?>
   <div id="content">

   <div id="content-header">
      <h1>Dashboard</h1>
   </div>
   <!-- #content-header -->


   <div id="content-container">

   <div>
      <h4 class="heading-inline">Weekly Sales Stats
         &nbsp;&nbsp;
         <small>For the week of Jun 15 - Jun 22, 2011</small>
         &nbsp;&nbsp;</h4>

      <div class="btn-group ">
         <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-clock-o"></i> &nbsp;
            Change Week <span class="caret"></span>
         </button>
         <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:;">Action</a></li>
            <li><a href="javascript:;">Another action</a></li>
            <li><a href="javascript:;">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="javascript:;">Separated link</a></li>
         </ul>
      </div>
   </div>

   <br/>


   <div class="row">

      <div class="col-md-3 col-sm-6">

         <a href="javascript:;" class="dashboard-stat primary">
            <div class="visual">
               <i class="fa fa-star"></i>
            </div>
            <!-- /.visual -->

            <div class="details">
               <span class="content">New Orders</span>
               <span class="value">1,236</span>
            </div>
            <!-- /.details -->

            <i class="fa fa-play-circle more"></i>

         </a> <!-- /.dashboard-stat -->

      </div>
      <!-- /.col-md-3 -->

      <div class="col-md-3 col-sm-6">

         <a href="javascript:;" class="dashboard-stat secondary">
            <div class="visual">
               <i class="fa fa-shopping-cart"></i>
            </div>
            <!-- /.visual -->

            <div class="details">
               <span class="content">Abandoned Carts</span>
               <span class="value">256</span>
            </div>
            <!-- /.details -->

            <i class="fa fa-play-circle more"></i>

         </a> <!-- /.dashboard-stat -->

      </div>
      <!-- /.col-md-3 -->

      <div class="col-md-3 col-sm-6">

         <a href="javascript:;" class="dashboard-stat tertiary">
            <div class="visual">
               <i class="fa fa-clock-o"></i>
            </div>
            <!-- /.visual -->

            <div class="details">
               <span class="content">Avg. Support Time</span>
               <span class="value">4:37</span>
            </div>
            <!-- /.details -->

            <i class="fa fa-play-circle more"></i>

         </a> <!-- /.dashboard-stat -->

      </div>
      <!-- /.col-md-3 -->

      <div class="col-md-3 col-sm-6">

         <a href="javascript:;" class="dashboard-stat">
            <div class="visual">
               <i class="fa fa-money"></i>
            </div>
            <!-- /.visual -->

            <div class="details">
               <span class="content">Total Revenue</span>
               <span class="value">$173K</span>
            </div>
            <!-- /.details -->

            <i class="fa fa-play-circle more"></i>

         </a> <!-- /.dashboard-stat -->

      </div>
      <!-- /.col-md-9 -->


   </div>
   <!-- /.row -->


   <div class="row">

   <div class="col-md-9">

   <div class="portlet">

      <div class="portlet-header">

         <h3>
            <i class="fa fa-bar-chart-o"></i>
            Area Chart
         </h3>

      </div>
      <!-- /.portlet-header -->

      <div class="portlet-content">

         <div class="pull-left">
            <div class="btn-group" data-toggle="buttons">
               <label class="btn btn-sm btn-default">
                  <input type="radio" name="options" id="option1"> Day
               </label>
               <label class="btn btn-sm btn-default">
                  <input type="radio" name="options" id="option2"> Week
               </label>
               <label class="btn btn-sm btn-default active">
                  <input type="radio" name="options" id="option3"> Month
               </label>
            </div>

            &nbsp;

            <div class="btn-group">
               <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                  Custom Date
                  <span class="caret"></span>
               </button>
               <ul class="dropdown-menu">
                  <li><a href="javascript:;">Dropdown link</a></li>
                  <li><a href="javascript:;">Dropdown link</a></li>
               </ul>
            </div>

         </div>

         <div class="pull-right">
            <div class="btn-group">
               <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-cog"></i> &nbsp;&nbsp;<span class="caret"></span>
               </button>
               <ul class="dropdown-menu pull-right" role="menu">
                  <li><a href="javascript:;">Action</a></li>
                  <li><a href="javascript:;">Another action</a></li>
                  <li><a href="javascript:;">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="javascript:;">Separated link</a></li>
               </ul>
            </div>
         </div>

         <div class="clear"></div>
         <hr/>


         <div id="area-chart" class="chart-holder" style="height: 250px"></div>
         <!-- /#bar-chart -->

      </div>
      <!-- /.portlet-content -->

   </div>
   <!-- /.portlet -->


   <div class="row">

   <div class="col-md-6">

      <div class="portlet">

         <div class="portlet-header">

            <h3>
               <i class="fa fa-money"></i>
               Recent Orders
            </h3>

            <ul class="portlet-tools pull-right">
               <li>
                  <button class="btn btn-sm btn-default">
                     Add Order
                  </button>
               </li>
            </ul>

         </div>
         <!-- /.portlet-header -->

         <div class="portlet-content">

            <div class="table-responsive">
               <table class="table">
                  <thead>
                  <tr>
                     <th>Purchased On</th>
                     <th>Items</th>
                     <th>Amount</th>
                     <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                     <td>09/21/2013</td>
                     <td>3</td>
                     <td>$108.35</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  <tr>
                     <td>09/21/2013</td>
                     <td>1</td>
                     <td>$30.89</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  <tr>
                     <td>09/20/2013</td>
                     <td>2</td>
                     <td>$52.06</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  <tr>
                     <td>09/19/2013</td>
                     <td>4</td>
                     <td>$73.54</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  <tr>
                     <td>09/19/2013</td>
                     <td>4</td>
                     <td>$73.54</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  <tr>
                     <td>09/19/2013</td>
                     <td>4</td>
                     <td>$73.54</td>
                     <td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
                              class="fa fa-chevron-right"></i></a></td>
                  </tr>
                  </tbody>
               </table>
            </div>
            <!-- /.table-responsive -->

            <hr/>

            <a href="javascript:;" class="btn btn-sm btn-secondary">View All Orders</a>


         </div>
         <!-- /.portlet-content -->

      </div>
      <!-- /.portlet -->


   </div>
   <!-- /.col-md-4 -->


   <div class="col-md-6">

      <div class="portlet">

         <div class="portlet-header">

            <h3>
               <i class="fa fa-group"></i>
               Recent Signups
            </h3>

            <ul class="portlet-tools pull-right">
               <li>
                  <button class="btn btn-sm btn-default">
                     Add User
                  </button>
               </li>
            </ul>

         </div>
         <!-- /.portlet-header -->

         <div class="portlet-content">


            <div class="table-responsive">

               <table id="user-signups" class="table table-striped table-checkable">
                  <thead>
                  <tr>
                     <th class="checkbox-column">
                        <input type="checkbox" id="check-all" class="icheck-input"/>
                     </th>
                     <th class="hidden-xs">First Name
                     </th>
                     <th>Last Name</th>
                     <th>Status
                     </th>

                     <th class="align-center">Approve
                     </th>

                  </tr>
                  </thead>

                  <tbody>
                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" name="actiony" value="joey" class="icheck-input">
                     </td>

                     <td class="hidden-xs">Joey</td>
                     <td>Greyson</td>
                     <td><span class="label label-success">Approved</span></td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>

                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" name="actiony" value="wolf" class="icheck-input">
                     </td>
                     <td class="hidden-xs">Wolf</td>
                     <td>Bud</td>
                     <td><span class="label label-default">Pending</span>
                     </td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>


                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" name="actiony" value="sam" class="icheck-input">
                     </td>

                     <td class="hidden-xs">Sam</td>
                     <td>Mitchell</td>
                     <td><span class="label label-success">Approved</span></td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>
                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" value="carlos" name="actiony" class="icheck-input">
                     </td>
                     <td class="hidden-xs">Carlos</td>
                     <td>Lopez</td>
                     <td><span class="label label-success">Approved</span></td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>


                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" name="actiony" value="rob" class="icheck-input">
                     </td>
                     <td class="hidden-xs">Rob</td>
                     <td>Johnson</td>
                     <td><span class="label label-default">Pending</span></td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>
                  <tr class="">
                     <td class="checkbox-column">
                        <input type="checkbox" value="mike" name="actiony" class="icheck-input">
                     </td>
                     <td class="hidden-xs">Mike</td>
                     <td>Jones</td>
                     <td><span class="label label-default">Pending</span></td>
                     <td class="align-center">
                        <a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
                           <i class="fa fa-check"></i>
                        </a>
                     </td>
                  </tr>

                  </tbody>
               </table>


            </div>
            <!-- /.table-responsive -->

            <hr/>

            Apply to Selected: &nbsp;&nbsp;
            <select id="apply-selected" name="table-select" class="ui-select2" style="width: 125px">
               <option value="">Select Action</option>
               <option value="approve">Approve</option>
               <option value="edit">Edit</option>
               <option value="delete">Delete</option>

            </select>

         </div>
         <!-- /.portlet-content -->

      </div>
      <!-- /.portlet -->

   </div>
   <!-- /.col-md-4 -->


   </div>
   <!-- /.row -->


   <div class="portlet">

      <div class="portlet-header">

         <h3>
            <i class="fa fa-calendar"></i>
            Full Calendar
         </h3>

      </div>
      <!-- /.portlet-header -->

      <div class="portlet-content">


         <div id="full-calendar"></div>


      </div>
      <!-- /.portlet-content -->

   </div>
   <!-- /.portlet -->


   </div>
   <!-- /.col-md-9 -->


   <div class="col-md-3">

   <div class="portlet">

      <div class="portlet-header">

         <h3>
            <i class="fa fa-bar-chart-o"></i>
            Donut Chart
         </h3>

      </div>
      <!-- /.portlet-header -->

      <div class="portlet-content">

         <div id="donut-chart" class="chart-holder" style="height: 250px"></div>


      </div>
      <!-- /.portlet-content -->

   </div>
   <!-- /.portlet -->


   <div class="portlet">

      <div class="portlet-header">

         <h3>
            <i class="fa fa-compass"></i>
            Traffic Overview
         </h3>

      </div>
      <!-- /.portlet-header -->

      <div class="portlet-content">


         <div class="progress-stat">

            <div class="stat-header">

               <div class="stat-label">
                  % New Visits
               </div>
               <!-- /.stat-label -->

               <div class="stat-value">
                  77.7%
               </div>
               <!-- /.stat-value -->

            </div>
            <!-- /stat-header -->

            <div class="progress progress-striped active">
               <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="77" aria-valuemin="0"
                    aria-valuemax="100" style="width: 77%">
                  <span class="sr-only">77.74% Visit Rate</span>
               </div>
            </div>
            <!-- /.progress -->

         </div>
         <!-- /.progress-stat -->

         <div class="progress-stat">

            <div class="stat-header">

               <div class="stat-label">
                  % Mobile Visitors
               </div>
               <!-- /.stat-label -->

               <div class="stat-value">
                  33.2%
               </div>
               <!-- /.stat-value -->

            </div>
            <!-- /stat-header -->

            <div class="progress progress-striped active">
               <div class="progress-bar progress-bar-tertiary" role="progressbar" aria-valuenow="33" aria-valuemin="0"
                    aria-valuemax="100" style="width: 33%">
                  <span class="sr-only">33% Mobile Visitors</span>
               </div>
            </div>
            <!-- /.progress -->

         </div>
         <!-- /.progress-stat -->

         <div class="progress-stat">

            <div class="stat-header">

               <div class="stat-label">
                  Bounce Rate
               </div>
               <!-- /.stat-label -->

               <div class="stat-value">
                  42.7%
               </div>
               <!-- /.stat-value -->

            </div>
            <!-- /stat-header -->

            <div class="progress progress-striped active">
               <div class="progress-bar progress-bar-secondary" role="progressbar" aria-valuenow="42" aria-valuemin="0"
                    aria-valuemax="100" style="width: 42%">
                  <span class="sr-only">42.7% Bounce Rate</span>
               </div>
            </div>
            <!-- /.progress -->

         </div>
         <!-- /.progress-stat -->

         <br/>

      </div>
      <!-- /.portlet-content -->

   </div>
   <!-- /.portlet -->


   <div class="portlet">

      <div class="portlet-header">

         <h3>
            <i class="fa fa-bar-chart-o"></i>
            Sparkline
         </h3>

      </div>
      <!-- /.portlet-header -->

      <div class="portlet-content">

         <div class="row row-marginless">

            <div class="spark-stat col-md-6 col-sm-6 col-xs-6">

               <div id="total-visits">32, 38, 46, 49, 53, 48, 47, 56</div>
               <span class="value">1,564</span>
               <h5>Total Visits</h5>

            </div>
            <!-- /.col -->

            <div class="spark-stat col-md-6 col-sm-6 col-xs-6">

               <div id="new-visits">32, 38, 46, 49, 53, 48, 47, 56</div>
               <span class="value">872</span>
               <h5>New Visits</h5>

            </div>
            <!-- /.col -->

         </div>
         <!-- /.row -->


         <div class="row row-marginless">

            <div class="spark-stat col-md-6 col-sm-6 col-xs-6">

               <div id="unique-visits">32, 38, 46, 49, 53, 48, 47, 56</div>
               <span class="value">845</span>
               <h5>Unique Visits</h5>

            </div>
            <!-- /.col -->

            <div class="spark-stat col-md-6 col-sm-6 col-xs-6">

               <div id="revenue-visits" data-bar-color="#c00">32, 38, 46, 49, 53, 48, 47, 56</div>
               <span class="value">$13,492</span>
               <h5>Revenue Visits</h5>

            </div>
            <!-- /.col -->

         </div>
         <!-- /.row -->

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


   </div> <!-- #content -->

   </div> <!-- #wrapper -->


<?php include ROOT_PATH . "app/views/footer.php"; ?>