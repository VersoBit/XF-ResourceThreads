<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceItem;

class Approve extends XFCP_Approve
{
    protected function onApprove()
    {
        parent::onApprove();

        $resource = $this->resource;

        $this->approveDiscussionThread($resource);
    }

    protected function approveDiscussionThread($resource)
    {
        // Approve resource's associated thread if unapproved
        if($resource->Discussion->discussion_state == 'moderated'){
            /** @var \XF\Service\Thread\Approver $threadApprover */
            $threadApprover = \XF::service('XF:Thread\Approver', $resource->Discussion);
            $threadApprover->approve();
        }
    }
}