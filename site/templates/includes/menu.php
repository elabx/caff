  <?php
  $matrix = $menu->menu_matrix;
  foreach ($matrix as $item){
      $menuPage = $item->page_single;

      if ($item->checkbox == 1) { //if dropdown
        echo "
        <li class='nav-item dropdown position-static'>
  				<a class='nav-link dropdown-toggle text-uppercase' href='#' id='$menuPage->name' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$menuPage->title</a>
  				
  				<div class='dropdown-menu mt-n1' aria-labelledby='$menuPage->name'>      				";
      				    //echo "<div class='row'><div class='col-md-12'><a class='dropdown-item text-uppercase border-bottom' href='$menuPage->url'>$menuPage->title</a></div></div>";
      				    echo "<div class='row'>";
                        foreach ($menuPage->children as $child){
                          echo "<div class='col-md'><a class='dropdown-item light text-uppercase text-wrap' href='$child->url'>$child->title</a>";
                          if ($child->template == "landing"){
                              foreach ($child->children->find("checkbox=1, limit=10") as $grandchild){
                                  echo "<a class='dropdown-item small text-wrap' href='$grandchild->url'>$grandchild->title</a>";
                              }  
                              echo "<a class='dropdown-item small mb-2 text-wrap' href='$child->url'>See all</a>";
                          }
                          echo "</div>";
                        }
                    echo "
                    </div>
                </div>";
      }elseif ($item->checkbox == 0) { //no dropdown
        echo "<li class='nav-item text-uppercase'><a class='nav-link' href='$menuPage->url'>$menuPage->title</a></li>";
      }
    }
    echo "</ul>";
    //Protected area
    //echo "<span class='nav-item'><a class='nav-link' href='{$domain}protected'><i class='bi bi-lock-fill'></i></a></span>";
    
    //Edit links for superusers
    if($user->hasPermission('page-publish')) {
    	echo "<span class='nav-item'><a class='nav-link text-edit' href='{$config->urls->admin}page/edit/?id={$page->id}'>Edit</a></span>";
    	echo "<span class='nav-item'><a class='nav-link text-edit' href='{$config->urls->admin}page'>All</a></span>";
    }else{
    }


    //Search
    echo "<span class='nav-item dropdown position-static'>
    			<a class='nav-link dropdown-toggle' href='#' id='search' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
    				Search
    			</a>

    			<div class='dropdown-menu w-100' aria-labelledby='search'>";
    				include("searchform.php");
    			echo "</div>
    	</span>
    	";
      ?>