<?php

/**
 * Component: Assign Members Table
 *
 * expects:
 * - $members
 * - $assignedIds (array)
 *
 * ids (must match JS):
 * - #memberList
 * - #selectAllHeader
 */

?>

<div class="rounded-md overflow-hidden bg-white">

    <div class="max-h-80 overflow-auto">
        <table class="w-full text-sm border-collapse">

            <!-- TABLE HEADER -->
            <thead class="sticky top-0 z-10 bg-gray-50 border-b">
                <tr class="text-xs font-semibold text-gray-600">
                    <th class="w-10 px-3 py-2 text-center">
                        <input type="checkbox"
                            id="selectAllHeader"
                            class="w-4 h-4 accent-green-600">
                    </th>
                    <th class="px-3 py-2 text-left">Name</th>
                    <th class="px-3 py-2 text-left">Email</th>
                    <th class="px-3 py-2 text-right">ID</th>
                </tr>
            </thead>

            <tbody id="memberList" class="divide-y">
                <?php if (empty($members)): ?>
                    <tr id="emptyRow">
                        <td colspan="4" class="text-center text-gray-500 py-10">
                            No member available
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <tr class="memberItem cursor-pointer hover:bg-gray-50 transition">
                            <td class="px-3 py-2 w-10 text-center align-middle">
                                <input type="checkbox"
                                    class="w-4 h-4 accent-green-600"
                                    name="member_ids[]"
                                    value="<?= (int)$m['id'] ?>"
                                    <?= in_array((int)$m['id'], $assignedIds ?? [], true) ? 'checked' : '' ?>>
                            </td>

                            <td class="px-3 py-2 align-middle font-medium text-gray-800 truncate">
                                <?= e($m['name']) ?>
                            </td>

                            <td class="px-3 py-2 align-middle text-gray-500 truncate">
                                <?= e($m['email']) ?>
                            </td>

                            <td class="px-3 py-2 align-middle text-right text-xs text-gray-400">
                                #<?= (int)$m['id'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!--always exist (hidden by default) -->
                    <tr id="noResultRow" style="display:none;">
                        <td colspan="4" class="text-center text-gray-500 py-3">
                            No users found 😢
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>