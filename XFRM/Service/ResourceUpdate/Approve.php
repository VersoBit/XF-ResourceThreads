<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceUpdate;

class Approve extends XFCP_Approve
{
    protected function onApprove()
    {
        parent::onApprove();

        $resource = $this->resource;

        // Approve resource's associated thread if unapproved
        if($resource->Discussion->discussion_state == 'moderated'){
            /** @var \XF\Service\ProfilePost\Approver $approver */
            $approver = \XF::service('XF:ProfilePost\Approver', $entity);
            $approver->approve();

            /** @var \XF\Service\Thread\Approver $threadApprover */
            $threadApprover = \XF::service('XF:Thread\Approver', $resource->Discussion);
            $threadApprover->approve();
        }
    }
}