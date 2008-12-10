<?php
/*
 * Laconica - a distributed open-source microblogging tool
 * Copyright (C) 2008, Controlez-Vous, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('LACONICA')) { exit(1); }

require_once(INSTALLDIR.'/lib/rssaction.php');

// Formatting of RSS handled by Rss10Action

class NoticesearchrssAction extends Rss10Action
{

    function init()
    {
        return true;
    }

    function get_notices($limit=0)
    {

        $q = $this->trimmed('q');
        $notices = array();

        $notice = new Notice();

        # lcase it for comparison
        $q = strtolower($q);

        $search_engine = $notice->getSearchEngine('identica_notices');
        $search_engine->set_sort_mode('chron');

        if (!$limit) $limit = 20;
        $search_engine->limit(0, $limit, true);
        $search_engine->query($q);
        $notice->find();

        while ($notice->fetch()) {
            $notices[] = clone($notice);
        }

        return $notices;
    }

    function get_channel()
    {
        global $config;
        $q = $this->trimmed('q');
        $c = array('url' => common_local_url('noticesearchrss', array('q' => $q)),
                   'title' => $config['site']['name'] . sprintf(_(' Search Stream for "%s"'), $q),
                   'link' => common_local_url('noticesearch', array('q' => $q)),
                   'description' => sprintf(_('All updates matching search term "%s"'), $q));
        return $c;
    }

    function get_image()
    {
        return null;
    }
}