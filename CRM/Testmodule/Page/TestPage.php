<?php

require_once 'CRM/Core/Page.php';

class CRM_Testmodule_Page_TestPage extends CRM_Core_Page {
	
	 static $_links = NULL;
  public function run() {
    
    $contact_id=CRM_Utils_Request::retrieve('contact_id', 'Integer');
   $query = "
        SELECT cp.*
        FROM civicrm_pcp cp WHERE cp.contact_id=$contact_id
        " ;

       
    $epages = CRM_Core_DAO::executeQuery($query);
    while ($epages->fetch()) {
    		$campaign_title = $epages->title;
    		$contact = CRM_Contact_BAO_Contact::getDisplayAndImage($epages->contact_id);
      		$status_id = CRM_PCP_BAO_PCP::buildOptions('status_id', 'create');
      		$status = $status_id[$epages->status_id];
      		$contribution_page_title = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_ContributionPage',$epages->page_id, 'title');
			    $contribution_page_id = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_ContributionPage',$epages->page_id, 'id');
      		$distribution_details = CRM_Contribute_BAO_Contribution::getCurrentandGoalAmount($contribution_page_id);
      		$amount_raised = $distribution_details[0];
      		$num_of_contribution = $distribution_details[1];

            $query = "
        SELECT count(*) as count
        FROM civicrm_contribution ct WHERE ct.contribution_page_id=$contribution_page_id
        " ;
         $con_pages = CRM_Core_DAO::executeQuery($query);
          $num_of_contribution=0;
         while ($con_pages->fetch()) {
           $num_of_contribution = $con_pages->count;
         }
         
      		$target_amount=$epages->goal_amount;
      		
      		 $pcpSummary[$epages->id] = array(
        		'campaign_title' => $campaign_title,
        		'contact' => $contact,
        		'status' => $status,
        		'contribution_page_title' => $contribution_page_title,
        		'num_of_contribution' => $num_of_contribution,
        		'amount_raised' => $amount_raised,
        		'target_amount' => $target_amount,
        		'action' => CRM_Core_Action::formLink(self::links(), $action,
          		array('id' => $epages->id), ts('more'), FALSE, 'contributionpage.pcp.list', 'PCP', $epages->id
        ),
        		
      			);
      
      		 $this->assign('rows', $pcpSummary); 	
      
    }

    

    parent::run();
  }

public function &links() {
    if (!(self::$_links)) {
      // helper variable for nicer formatting
      $deleteExtra = ts('Are you sure you want to delete this Campaign Page ?');

      self::$_links = array(
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/pcp/info',
          'qs' => 'action=update&reset=1&id=%%id%%&context=dashboard',
          'title' => ts('Edit Personal Campaign Page'),
        )
      );
    }
    return self::$_links;
  }

public function getCurrentandGoalAmount($pageID,$contactID) {
    $query = "
SELECT p.goal_amount as goal, sum( c.total_amount ) as total
  FROM civicrm_contribution_page p,
       civicrm_contribution      c
 WHERE p.id = c.contribution_page_id
   AND p.id = %1
   AND c.cancel_date is null
   AND c.contact_id=$contactID
   
GROUP BY p.id
";

    $config = CRM_Core_Config::singleton();
    $params = array(1 => array($pageID, 'Integer'));
    $dao = CRM_Core_DAO::executeQuery($query, $params);

    if ($dao->fetch()) {
      return array($dao->goal, $dao->total);
    }
    else {
      return array(NULL, NULL);
    }
  }

}
