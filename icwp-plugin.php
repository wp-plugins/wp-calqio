<?php
/*
Plugin Name: Calq.io For WordPress
Plugin URI: http://icwp.io/home
Description: Easily Integrate Calq.io Analytics Into Your WordPress Sites
Version: 1.0.1
Text Domain: wp-calqio
Author: iControlWP
Author URI: http://www.icontrolwp.com/
*/

/**
 * Copyright (c) 2015 iControlWP <support@icontrolwp.com>
 * All rights reserved.
 *
 * "Calq.io For WordPress" is distributed under the GNU General Public License, Version 2,
 * June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110, USA
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

if ( !defined( 'ICWP_DS' ) ) {
	define( 'ICWP_DS', DIRECTORY_SEPARATOR );
}

if ( !function_exists( '_calqio_e' ) ) {
	function _calqio_e( $sStr ) {
		_e( $sStr, 'wp-calqio' );
	}
}
if ( !function_exists( '_calqio__' ) ) {
	function _calqio__( $sStr ) {
		return __( $sStr, 'wp-calqio' );
	}
}


// By requiring this file now, we assume we wont need to require it anywhere else.
require_once( dirname(__FILE__).ICWP_DS.'src'.ICWP_DS.'common'.ICWP_DS.'icwp-foundation.php' );

class ICWP_CALQIO_Plugin extends ICWP_CALQIO_Foundation {

	/**
	 * @var ICWP_CALQIO_Plugin_Controller
	 */
	protected static $oPluginController;

	/**
	 * @param ICWP_CALQIO_Plugin_Controller $oPluginController
	 */
	public function __construct( ICWP_CALQIO_Plugin_Controller $oPluginController ) {
		self::$oPluginController = $oPluginController;
		$this->getController()->loadAllFeatures();
	}

	/**
	 * @return ICWP_CALQIO_Plugin_Controller
	 */
	public static function getController() {
		return self::$oPluginController;
	}
}

require_once( dirname(__FILE__).ICWP_DS.'icwp-plugin-controller.php' );

$oICWP_App_Controller = ICWP_CALQIO_Plugin_Controller::GetInstance( __FILE__ );
if ( !is_null( $oICWP_App_Controller ) ) {
	$g_oCalqioPlugin = new ICWP_CALQIO_Plugin( $oICWP_App_Controller );
}