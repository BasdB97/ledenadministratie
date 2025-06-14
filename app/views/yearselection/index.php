<?php
if (isset($_SESSION['bookyear'])) {
  include_once APP_ROOT . '/views/includes/header.php';
} else {
?>
  <!DOCTYPE html>
  <html lang="nl">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boekjaar selectie</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: {
                light: '#60a5fa',
                DEFAULT: '#3b82f6',
                dark: '#2563eb',
              },
              bgDark: '#1e3a8a',
              bgLight: '#f5f5f5',
              borderColor: '#4b72bf',
            }
          }
        }
      }
    </script>
  </head>

  <body class="h-screen overflow-hidden flex flex-col bg-gray-100">
    <header class="bg-primary text-white shadow-md">
      <div class="container mx-auto px-4 py-3">
        <h1 class="text-2xl text-center font-bold"><?php echo SITE_NAME; ?></h1>
      </div>
    </header>

    <main class="flex-1 flex justify-center items-center overflow-y-auto p-5 bg-bgLight relative">
    <?php } ?>
    <div class="max-w-md mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full">
      <div class="bg-white rounded-lg shadow-xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center mb-6 text-primary">Selecteer Boekjaar</h2>
        <p class="text-center text-gray-600 mb-6">Kies een boekjaar om door te gaan</p>

        <form action="<?php echo URL_ROOT; ?>/yearselection/selectYear" method="post" class="space-y-6">
          <div>
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">
              Boekjaar <span class="text-red-500">*</span>
            </label>
            <select name="year" id="year" class="mt-1 block w-full rounded-md border-2 border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50" required>
              <option value="">Selecteer een boekjaar</option>
              <?php foreach ($data['bookyears'] as $bookyear): ?>
                <option>
                  <?php echo $bookyear->year; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out">
              Doorgaan
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php include_once APP_ROOT . '/views/includes/footer.php'; ?>