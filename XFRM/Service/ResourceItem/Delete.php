<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceItem;

class Delete extends XFCP_Delete
{
    public function delete($type, $reason = '')
    {
        $result = parent::delete($type, $reason = '');

        $resource = $this->resource;

        $this->deleteDiscussionThread($resource);

        return $result;
    }

    protected function deleteDiscussionThread($resource)
    {
        // Delete resource's associated discussion thread if was unapproved
        if($resource->Discussion->discussion_state == 'moderated'){
            /** @var \XF\Service\Thread\Deleter $threadDeleter */
            $threadDeleter = $this->service('XF:Thread\Deleter', $resource->Discussion);
            $threadDeleter->delete('soft', \XF::phrase('xfrm_resource').' deleted');
        }
    }
}