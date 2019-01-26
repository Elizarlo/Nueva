<?php
/**
 * Kunena Plugin
 *
 * @package         Kunena.Plugins
 * @subpackage      Easyprofile
 *
 * @copyright       Copyright (C) 2008 - 2018 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Factory;

/**
 * Class KunenaAvatarEasyprofile
 * @since Kunena
 */
class KunenaAvatarEasyprofile extends KunenaAvatar
{
	/**
	 * @var null
	 * @since Kunena
	 */
	protected $params = null;

	/**
	 * KunenaAvatarEasyprofile constructor.
	 *
	 * @param $params
	 *
	 * @since Kunena
	 */
	public function __construct($params)
	{
		$this->params = $params;
	}

	/**
	 * @return mixed
	 * @since Kunena
	 */
	public function getEditURL()
	{
		return JRoute::_('index.php?option=com_jsn&view=profile');
	}

	/**
	 * @param $user
	 * @param $sizex
	 * @param $sizey
	 *
	 * @return string
	 * @throws Exception
	 * @since Kunena
	 */
	public function _getURL($user, $sizex, $sizey)
	{
		if (!$user->userid == 0)
		{
			$user = KunenaFactory::getUser($user->userid);
			$user = JsnHelper::getUser($user->userid);

			if ($sizex <= 50)
			{
				$avatar = \Joomla\CMS\Uri\Uri::root(true) . '/' . $user->getValue('avatar_mini');
			}
			else
			{
				$avatar = \Joomla\CMS\Uri\Uri::root(true) . '/' . $user->getValue('avatar');
			}
		}
		elseif ($this->params->get('guestavatar', "easyprofile") == "easyprofile")
		{
			$avatar = \Joomla\CMS\Uri\Uri::root(true) . '/components/com_jsn/assets/img/default.jpg';
		}
		else
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select('params')->from('#__jsn_fields')->where('alias=\'avatar\'');
			$db->setQuery($query);
			$params   = $db->loadResult();
			$registry = new \Joomla\Registry\Registry;
			$registry->loadString($params);
			$params = $registry->toArray();

			if ($params['image_defaultvalue'] <> "")
			{
				$avatar = \Joomla\CMS\Uri\Uri::root(true) . '/' . $params['image_defaultvalue'];
			}
			else
			{
				$avatar = \Joomla\CMS\Uri\Uri::root(true) . '/components/com_jsn/assets/img/default.jpg';
			}
		}

		return $avatar;
	}
}
