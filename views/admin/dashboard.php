<!DOCTYPE html>
<html lang="en">

<?php include $this->resolve("partials/_head.php"); ?>

<body class="bg-indigo-50 font-['Outfit']">
    <!-- Start Header -->
    <?php include $this->resolve("partials/_header.php"); ?>
    <!-- End Header -->

    <!-- Start Main Content Area -->
    <section class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
        <div class="flex items-center justify-between border-b border-gray-200 pb-4">
            <h4 class="font-medium">Transaction List</h4>
            <div class="flex space-x-4">
            </div>
        </div>
        <!-- Search Form -->
        <form method="GET" class="mt-4 w-full">
            <div class="flex">
                <input name="s" type="text" value="<?php echo escape((string) $searchTerm); ?>" class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Enter search term" />
                <button type="submit" class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Search
                </button>
            </div>
        </form>
        <!-- Transaction List -->
        <table class="table-auto min-w-full divide-y divide-gray-300 mt-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Description
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Amount
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Receipt(s)
                    </th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-900">
                        Date
                    </th>
                    <th>User</th>
                </tr>
            </thead>
            <!-- Transaction Table Body -->
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php foreach ($transactions as $transaction) : ?>
                    <tr>
                        <!-- Description -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($transaction['description']); ?></td>
                        <!-- Amount -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($transaction['amount']); ?></td>
                        <!-- Receipt List -->
                        <td class="p-4 text-sm text-gray-600">
                            <?php foreach ($transaction['receipts'] as $receipt) : ?>
                                <div class="inline-block relative cursor-pointer">
                                    <a href="/admin/users/<?php echo escape($transaction['user_id']); ?>/receipt/<?php echo escape($receipt['id']); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgb(109 40 217)" class="w-10 h-10">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <!-- Date -->
                        <td class="p-4 text-sm text-gray-600"><?php echo escape($transaction['formatted_date']); ?></td>
                        <!-- User -->
                        <td class="p-4 text-sm text-gray-600 flex justify-center space-x-2">
                            <a href="/admin/users/<?php echo escape($transaction['user_id']); ?>" class="p-2 bg-indigo-50 text-xs text-indigo-600 hover:bg-indigo-500 hover:text-white transition rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <?php echo escape($transaction['user_email']); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-6">
            <!-- Previous Page Link -->
            <div class="-mt-px flex w-0 flex-1">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/?<?php echo escape($previousPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                        </svg>
                        Previous
                    </a>
                <?php endif; ?>
            </div>
            <!-- Pages Link -->
            <div class="hidden md:-mt-px md:flex">
                <?php foreach ($pageLinks as $pageNum => $pageLink) : ?>
                    <a href="/?<?php echo escape($pageLink); ?>" class="<?php echo $pageNum + 1 === $currentPage ? "border-indigo-500 text-indigo-600" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"; ?> inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium">
                        <?php echo $pageNum + 1; ?>
                    </a>
                <?php endforeach; ?>
                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
            </div>
            <!-- Next Page Link -->
            <div class="-mt-px flex w-0 flex-1 justify-end">
                <?php if ($currentPage < $lastPage) : ?>
                    <a href="/admin/?<?php echo escape($nextPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        Next
                        <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </section>
    <!-- End Main Content Area -->

    <!-- Footer -->
    <?php include $this->resolve("/partials/_footer.php"); ?>
    <!-- End Footer -->
</body>

</html>