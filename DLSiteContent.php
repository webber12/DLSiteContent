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

    public function scopeDepth($query, $parents = '', $depth = 1, $showParent = false) {
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
        while ((--$depth) > 0) {
            $ids = static::whereIn('parent', $parents)->where('isfolder', '=', '1')->get()->pluck('id')->toArray();
            if (!empty($ids)) {
                $result = static::getIDs($ids, $depth, $result);
            } else {
                return $result;
            }
        }
        return $result;
    }
}
