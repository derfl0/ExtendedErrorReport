<table class="default">
    <caption>
        <?= htmlReady($errors[0]['text']) ?>
    </caption>
    <thead>

        <tr>
            <td>
                <?= htmlReady($errors[0]['file']) ?>
            </td>
        </tr>
        <tr>
            <td>
                Line: <?= htmlReady($errors[0]['line']) ?>
            </td>
        </tr>

        <tr>
            <th>
                <?= _('Datum') ?>
            </th>
            <th>
                <?= _('Benutzer') ?>
            </th>
            <th>
                <?= _('IP') ?>
            </th>
            <th>
                <?= _('XHR') ?>
            </th>
            <th>
                <?= _('URL') ?>
            </th>
            <th>
                <?= _('Data') ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($errors as $entry): ?>
            <tr>
                <td>
                    <?= strftime('%d.%m.%y  - %T', $entry['mkdate']) ?>
                </td>
                <td>
                    <?= ObjectdisplayHelper::link(User::find($entry['user_id'])) ?>
                </td>
                <td>
                    <?= htmlReady($entry['ip']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['xhr']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['requested_url']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['request_data']) ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>