<?php
class Menu extends Libraries
{
	public function __construct()
	{
		parent::__construct();
		__extends($this);
		$this->allowed_interface	=	array( 'admin' , 'account' );
	}
	public function getControllerSubmenu($element,$ulclass="",$liclass="")
	{
		if(is_array($element))
		{
			if(array_key_exists('PAGE_CHILDS',$element))
			{
				if(is_array($element['PAGE_CHILDS']))
				{
					?>
	
	<ul class="<?php echo $ulclass;?>">
		<?php
					foreach($element['PAGE_CHILDS'] as $p)
					{
						if($p['PAGE_MODULES'] == '#LINK#')
						{
						?>
		<li class="<?php echo $liclass;?>"><a href="<?php echo $p['PAGE_LINK'];?>"><?php echo ucwords($p['PAGE_NAMES']);?></a>
			<?php $this->getControllerSubmenu($p,$ulclass,$liclass);?>
		</li>
		<?php
						}
						else
						{
							?>
		<li class="<?php echo $liclass;?>"><a href="<?php echo $this->url->site_url(array($p['PAGE_CNAME']));?>"><?php echo ucwords($p['PAGE_NAMES']);?></a>
			<?php $this->getControllerSubmenu($p,$ulclass,$liclass);?>
		</li>
		<?php
						}
					}
					?>
	</ul>
	<?php
				}
			}
		}
	}
	public function add_admin_menu_core( $namespace , $config )
	{
		return $this->add_menu_core( 'admin' , $namespace , $config );
	}
	public function get_admin_menu_core( $namespace )
	{
		return $this->get_menu_core( 'admin' , $namespace );
	}
	public function get_account_menu_core( $namespace )
	{
		return $this->get_menu_core( 'account' , $namespace );
	}
	public function add_menu_core( $interface , $namespace , $config )
	{
		if( in_array( $interface , $this->allowed_interface ) )
		{
			$core_menus	=	( $array = get_core_vars( $interface . '_menus_core' ) ) ? $array : array();
			$core_menus[ $namespace ][]	=	$config;
			return set_core_vars( $interface . '_menus_core' , $core_menus );
		}
		return false;
	}
	public function	add_account_menu_core( $namespace , $config )
	{
		return $this->add_menu_core( 'account' , $namespace , $config );
	}
	public function get_menu_core( $interface , $namespace )
	{
		if( in_array( $interface , $this->allowed_interface ) )
		{
			$core_menus	=	get_core_vars( $interface . '_menus_core' );
			if( $core_menus )
			{
				$current_menu	=	riake( $namespace , $core_menus );
				if( $current_menu ) // If this key exists
				{
					$menu_status		=	'';
					$custom_ul_style	=	'';
					// Preloop, to check if this menu has an  active child
					foreach( $current_menu as $_menu )
					{
						if( riake( 'href' , $_menu ) == get_instance()->url->site_url() ) // if childs href key match current site_url, this child is active then.
						{
							$menu_status	=	'active';
							$custom_ul_style	= 'style="display: block;"';
						}
					}
					$class			=	is_array( $current_menu ) && count( $current_menu ) > 1 ? 'dropdown-submenu' : '';
					$loop_index		=	0;
					?>
					<li class="<?php echo $class . ' ' . $menu_status;?>">
					<?php
					foreach( $current_menu as $menu )
					{
						if( $class != '' ) // If has more than one child
						{
							$custom_style		=	get_instance()->url->site_url() == riake( 'href' , $menu , '#' ) ? 'style="color:#fff"' : '';
							if( $loop_index == 0 ) // First child, set a default page and first sub-menu.
							{
							?>
								<a <?php echo $custom_style;?> href="javascript:void(0)" class="dropdown-toggle <?php echo $menu_status;?>"> 
									<span class="pull-right auto"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span>
									<i class="<?php echo riake( 'icon' , $menu , 'fa fa-star' );?>"></i> 
									<span><?php echo riake( 'title' , $menu );?></span> 
								</a>
								<ul <?php echo $custom_ul_style;?> class="nav none dker">
									<li> <a <?php echo $custom_style;?> href="<?php echo riake( 'href' , $menu , '#' );?>"><?php echo riake( 'title' , $menu );?></a> </li>	
							<?php
							}
							else // after the first child, all are included as sub-menu
							{
								?>
								<li> <a <?php echo $custom_style;?> href="<?php echo riake( 'href' , $menu , '#' );?>"><?php echo riake( 'title' , $menu );?></a> </li>	
								<?php
							}
							if( $loop_index == ( count( $current_menu ) - 1 ) ) // we're at the end of the loop, so we close the "ul"
							{
								?>
								</ul>
								<?php
							}
						}
						else
						{
							?>
							 <a href="<?php echo riake( 'href' , $menu , '#' );?>"> <i class="<?php echo riake( 'icon' , $menu , 'fa fa-star' );?>"></i> <span><?php echo riake( 'title' , $menu );?></span> </a>
							<?php						
						}
						$loop_index++; // increment loop_index
					}
					?>
					</li>
					<?php
				}
			}
		}
		return false;
	}
}