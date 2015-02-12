<?php
/*
  Plugin Name: XCustomTables
  Description: Create custom List Tables to list / edit / delete records from a non wordpress table, in a wordpress-like form. Support one-to-many relationships.
  Version: 1.1
  Requires at least: 3.5
  Tested up to: 4.0.1  
  Author: Xavier Perez
  License: GPLv2 or later
*/
/*	Copyright (c) 2015  Xavier Perez

	All rights reserved.

	XCustomTables is distributed under the GNU General Public License, Version 2,
	June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
	St, Fifth Floor, Boston, MA 02110, USA

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
	ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
	ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */


// Load WP_List_Table if not loaded
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


// Load Textdomain 
if (!defined('XCTAB_LANG')) define('XCTAB_LANG','xctab');

load_plugin_textdomain('xctab', false, __DIR__ . '/languages');
if (file_exists(__DIR__ . '/languages/xctab-'.get_locale().'.mo'))
    load_textdomain( 'xctab', __DIR__ . '/languages/xctab-'.get_locale().'.mo' );
else
    load_textdomain( 'xctab', __DIR__ . '/languages/xctab-en_US.mo' );

if (!class_exists('xctBaseModel')) {
    require_once( __DIR__ . '/xctcore/class/xctBaseModel.php' );
}

// Default conf
include_once( __DIR__ . '/config/config.php' );
include_once( __DIR__ . '/xctcore/config/config.php' );

// TableName called from url params
global $tableName;

// TableName get from pageName
$tableName = isset($_REQUEST['page']) ? substr($_REQUEST['page'], 3) : '';

require __DIR__.'/xcustomtables.class.php';
require __DIR__.'/xcustomtables.functions.php';




