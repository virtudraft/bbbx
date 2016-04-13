<tr>
    <td>
        [[+bbbx.meeting.name]]
    </td>
    <td>
        [[+bbbx.meeting.description]]
    </td>
    <td>
        [[+bbbx.meeting.started_on:date=`%b %m, %Y %r`]]
    </td>
    <td>
        [[+bbbx.meeting.ended_on:date=`%b %m, %Y %r`]]
    </td>
    <td>
        [[+modx.user.id:is=`0`:then=`
        Please login
        `:else=`
        [[+bbbx.meeting.join_url:notempty=`
        <a href="[[+bbbx.meeting.join_url]]" target="_blank">Join</a>
        `]]
        `]]
    </td>
    <td>
        [[!bbbx.getRecordings? &meetingId=`[[+bbbx.meeting.meeting_id]]`]]
    </td>
</tr>