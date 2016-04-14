<tr>
    <td>
        [[+bbbx.meeting.name]]
    </td>
    <td>
        [[+bbbx.meeting.description]]
    </td>
    <td>
        [[+bbbx.meeting.started_on:date=`%a, %b %d, %Y %r`]]
    </td>
    <td>
        [[+bbbx.meeting.ended_on:date=`%a, %b %d, %Y %r`]]
    </td>
    <td>
        [[+bbbx.meeting.is_running:notempty=`

        [[+modx.user.id:is=`0`:then=`
        <a
            class="btn btn-success btn-sm"
            href="login"
            target="_blank"
            >Login</a>
        `:else=`

        [[+bbbx.meeting.join_url:notempty=`
        <a
            class="btn btn-success btn-sm"
            href="[[+bbbx.meeting.join_url]]"
            target="_blank"
            >Join</a>
        `]]

        `]]

        `]]
    </td>
    <td>
        [[!bbbx.getRecordings?
        &meetingId=`[[+bbbx.meeting.meeting_id]]`
        &toPlaceholder=`recordings.[[+bbbx.meeting.meeting_id]]`
        ]]

        [[+recordings.[[+bbbx.meeting.meeting_id]]:isnot:``:then=`

        [[+modx.user.id:is=`0`:then=`
        <a
            class="btn btn-success btn-sm"
            href="login"
            target="_blank"
            >Login</a>
        `:else=`
        [[+recordings.[[+bbbx.meeting.meeting_id]]]]
        `]]

        `:else=``]]
    </td>
</tr>