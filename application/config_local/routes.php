 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';

|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 		= 'admin';
$route['404_override'] 				= 'my404';
$route['translate_uri_dashes'] 		= FALSE;
$route['hidesignup']				= 'admin/signup';
$route['dashboard']					= 'admin/dashboard';
$route['profile/(:any)'] 			= 'admin/users/userDetail/$1';
$route['change_password/(:any)'] 	= 'admin/users/changePassword/$1';
$route['tasks'] 	= 'admin/tasks/index';
$route['task-detail/(:any)'] 	= 'admin/tasks/detail/$1';

/* invite company route */
$route['invite/(:any)']					= 'company/Share/index/$1';

// reset password
$route['resetmemberpassword/(:any)']					= 'company/Share/resetmemberpassword/$1';


$route['commonresetmemberpassword/(:any)']					= 'company/Share/commonresetmemberpassword/$1';



$route['invitation/(:any)']					= 'company/Share/memeberinvitaion/$1';

/*  company manage  */
$route['company'] = 'admin/company/index';
$route['company-detail/(:any)'] 	= 'admin/company/detail/$1';


/*contractor routes*/
$route['contractor/login'] = 'contractor/ContractorAdmin/index';
$route['contractor/signup']='contractor/ContractorAdmin/signup';
$route['contractor/dashboard'] = 'contractor/ContractorAdmin/dashboard';
$route['contractor/profile'] = 'contractor/ContractorAdmin/profile';

/*contractor routes end*/


/* admin /company routes */
$route['admin'] = 'company/admin/index';

$route['admin/logout'] = 'company/admin/logout';
$route['admin_logout/logout'] = 'company/admin/admin_logout';

$route['admin/forgot'] = 'company/admin/forgot';
//myforgot
$route['admin/myforgot'] = 'admin/admin/forgot';

$route['admin/login'] = 'company/admin/index';

$route['admin/dashboard'] = 'company/admin/dashboard';

$route['admin/chat']					= 'company/admin/chat';

$route['admin/profile'] = 'company/admin/profile';

$route['admin/crew_member'] = 'company/Crew/index';

$route['admin/crew-detail/(:any)'] 	= 'company/Crew/detail/$1';

$route['admin/contractor'] = 'company/Contractor/index';

$route['admin/contractor-detail/(:any)'] 	= 'company/Contractor/detail/$1';

$route['admin/client'] = 'company/Client/index';

$route['admin/client-detail/(:any)'] 	= 'company/Client/detail/$1';

$route['admin/project'] = 'company/Project/index';

$route['admin/project-detail/(:any)'] 	= 'company/Project/detail/$1';

//complete profile

$route['admin/complete_profile'] 	= 'company/admin/complete_profile';

//Task
$route['admin/tasks'] 	= 'company/tasks/index';
$route['admin/task-detail/(:any)'] 	= 'company/tasks/detail/$1';
 





