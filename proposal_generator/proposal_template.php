<?php
declare(strict_types=1);

/**
 * Generates HTML proposal from extracted data.
 *
 * @param array $data
 * @return string HTML
 */
function generateProposal(array $data): string
{
    $none = 'None found';
    $name = trim((string)($data['name'] ?? 'Your Institution'));
    $prettyName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // Static pricing table (2025 SA averages)
    $pricing = [
        ['Service' => 'Website Development (Basic)', 'Cost' => 'R10,000 - R15,000', 'Timeline' => '4-6 weeks'],
        ['Service' => 'Website Development (Advanced)', 'Cost' => 'R20,000 - R30,000', 'Timeline' => '6-8 weeks'],
        ['Service' => 'Social Media Setup', 'Cost' => 'R5,000 - R8,000 (one-time)', 'Timeline' => '2 weeks'],
        ['Service' => 'Social Media Management (Monthly)', 'Cost' => 'R3,500 - R6,000', 'Timeline' => 'Ongoing'],
        ['Service' => 'Social Media Advertising', 'Cost' => 'R3,000 - R5,000/month + ad budget', 'Timeline' => 'Ongoing'],
    ];

    $address = htmlspecialchars((string)($data['address'] ?? 'N/A'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $phones = array_map(static fn($p) => htmlspecialchars((string)$p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), (array)($data['phone'] ?? []));
    $email = htmlspecialchars((string)($data['email'] ?? 'N/A'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $website = htmlspecialchars((string)($data['website'] ?? $none), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $accs = (array)($data['accreditations'] ?? []);
    $accs = array_map(static fn($a) => htmlspecialchars((string)$a, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), $accs);
    $courses = (array)($data['courses'] ?? []);
    $courses = array_map(static fn($c) => htmlspecialchars((string)$c, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), $courses);

    ob_start();
    ?>
    <article>
        <header>
            <h2>Digital Services Proposal for <?= $prettyName ?></h2>
            <p>
                Address: <?= $address ?> | Contact: <?= htmlspecialchars(implode(', ', $phones), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> | Email: <?= $email ?>
            </p>
        </header>

        <section>
            <h3>Extracted Details</h3>
            <ul>
                <li><strong>Accreditations:</strong> <?= $accs ? implode(', ', $accs) : $none ?></li>
                <li><strong>Courses:</strong> <?= $courses ? implode(', ', $courses) : $none ?></li>
                <li><strong>Website:</strong> <?= $website ?></li>
            </ul>
        </section>

        <section>
            <h3>Objectives</h3>
            <ul>
                <li>Increase visibility and student enrolments.</li>
                <li>Showcase courses and accreditations online.</li>
                <li>Enable easy registrations via forms and WhatsApp CTA.</li>
            </ul>
        </section>

        <section>
            <h3>Scope of Work</h3>
            <p>
                Design and develop a responsive website (Home, About, Courses, Admissions, Contact) with enquiry forms,
                basic SEO, WhatsApp click-to-chat, and Google Analytics. Social media setup for Facebook and Instagram
                with content calendar, branding assets, and targeted ad campaigns.
            </p>
        </section>

        <section>
            <h3>Pricing Breakdown (ZAR)</h3>
            <table>
                <thead>
                <tr>
                    <th>Service</th>
                    <th>Cost</th>
                    <th>Timeline</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pricing as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['Service'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['Cost'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['Timeline'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p class="meta">Starter Package example: R26,000 once-off + R5,000/month. Custom combinations available.</p>
        </section>

        <section>
            <h3>Assumptions & Notes</h3>
            <ul>
                <li>Pricing excludes VAT and paid ad budgets.</li>
                <li>Copy, images, and approvals provided by <?= $prettyName ?>.</li>
                <li>Two rounds of revisions per deliverable included.</li>
            </ul>
        </section>

        <footer>
            <p>Best regards,<br/>Your Digital Partner</p>
        </footer>
    </article>
    <?php
    return (string)ob_get_clean();
}
