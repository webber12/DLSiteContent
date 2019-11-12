<?php namespace EvolutionCMS\Custom;

use \EvolutionCMS\Models\SiteContent;

class DLSiteContent extends SiteContent
{

    public function scopePublished($query)
    {
        return $query->where('published', '1');
    }

    public function scopeUnpublished($query)
    {
        return $query->where('published', '0');
    }

    public function scopeDepth($query, $parents = '', $depth = 1, $showParent = false)
    {
        $parents = explode(',', $parents);
        if (count($parents) > 0) {
            $ids = static::getIDs($parents, $depth);
            if (!empty($ids)) {
                $query = $query->whereIn('parent', $ids);
                if (!$showParent) {
                    $query = $query->whereNotIn('id', $ids);
                }
            }
        }
        return $query;
    }

    public static function getIDs($parents = array(), $depth = 1, $result = array())
    {
        $result = array_merge($result, $parents);
        if ($depth <= 1) return $result;
        $ids = static::whereIn('parent', $parents)->where('isfolder', '=', '1')->get()->pluck('id')->toArray();
        if (!empty($ids)) {
            $depth--;
            $result = static::getIDs($ids, $depth, $result);
        } else {
            return $result;
        }
        return $result;
    }

    public function scopeWithTVs($query, $tvList = array())
    {
        if (!empty($tvList)) {
            $tvList = array_unique($tvList);
            $tvs = SiteTmplvar::whereIn('name', $tvList)->get()->pluck('id', 'name')->toArray();
            foreach ($tvs as $tvname => $tvid) {
                $query = $query->leftJoin('site_tmplvar_contentvalues as tv_' . $tvname, function ($join) use ($tvid, $tvname) {
                    $join->on('site_content.id', '=', 'tv_' . $tvname . '.contentid')->where('tv_' . $tvname . '.tmplvarid', '=', $tvid);
                });
            }
            $query->groupBy('site_content.id');
        }
        return $query;
    }
}
