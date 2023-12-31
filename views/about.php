<!DOCTYPE html>
<html lang="en">

<?php include $this->resolve("partials/_head.php"); ?>

<body class="bg-indigo-50 font-['Outfit']">
    <!-- Start Header -->
    <?php include $this->resolve("partials/_header.php"); ?>
    <!-- End Header -->

    <!-- Start Main Content Area -->
    <section class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
        <!-- Page Title -->
        <h3><?php echo escape($title) ?></h3>

        <hr />

        <!-- Escaping Data -->
        <p>This is a desktop app created to track expenses by individual user.
            It is also possible to keep track of receipts for each expense by attaching them.
        </p>
    </section>
    <!-- End Main Content Area -->

    <!-- Footer -->
    <?php include $this->resolve("/partials/_footer.php"); ?>
    <!-- End Footer -->
</body>

</html>