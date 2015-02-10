<table class="default">
    <caption>
        <?= htmlReady($name) ?>
    </caption>
    <thead>
        <tr>
            <th>
                <?= _('Meldung') ?>
            </th>
            <th>
                <?= _('Datei') ?>
            </th>
            <th>
                <?= _('Zeile') ?>
            </th>
            <th>
                <?= _('Häufung') ?>
            </th>
            <th>
                <?= _('Details') ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($content as $entry): ?>
            <tr>
                <td>
                    <?= htmlReady($entry['text']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['file']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['line']) ?>
                </td>
                <td>
                    <?= htmlReady($entry['occurance']) ?>
                </td>
                <td>
                    <a href="<?= $controller->url_for('show/details', array("line" => $entry['line'], "file" => $entry['file'])) ?>"><?= _('Anzeigen') ?></a>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>