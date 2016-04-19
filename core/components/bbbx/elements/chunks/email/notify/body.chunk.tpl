<p>Hello [[+profile.fullname:default=`[[+user.username]]`]].</p>

<p>
    A meeting schedule has been set with this detail:
</p>
<table>
    <tr>
        <td>Name</td>
        <td>:</td>
        <td>[[+meeting.name]]</td>
    </tr>
    <tr>
        <td>Description</td>
        <td>:</td>
        <td>[[+meeting.description]]</td>
    </tr>
    <tr>
        <td>Start</td>
        <td>:</td>
        <td>[[+meeting.started_on:date=`%a, %b %d, %Y %r`]]</td>
    </tr>
    <tr>
        <td>End</td>
        <td>:</td>
        <td>[[+meeting.ended_on:date=`%a, %b %d, %Y %r`]]</td>
    </tr>
</table>

<p>Thank you.</p>
<p></p>
<p>[[++site_name]]</p>