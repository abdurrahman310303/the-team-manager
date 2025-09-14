<?php
$title = 'Create Daily Report - Team Manager';
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Daily Work Report</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Record your daily work activities, challenges, and plans for tomorrow.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/daily-reports/store" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="work_done" class="block text-sm font-medium text-gray-700">Work Completed Today *</label>
                            <textarea name="work_done" id="work_done" rows="4" required
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Describe what you accomplished today..."></textarea>
                        </div>

                        <div>
                            <label for="hours_worked" class="block text-sm font-medium text-gray-700">Hours Worked *</label>
                            <input type="number" name="hours_worked" id="hours_worked" step="0.5" min="0" max="24" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="8.0">
                        </div>

                        <div>
                            <label for="challenges" class="block text-sm font-medium text-gray-700">Challenges Faced</label>
                            <textarea name="challenges" id="challenges" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Any challenges or obstacles you encountered..."></textarea>
                        </div>

                        <div>
                            <label for="tomorrow_plan" class="block text-sm font-medium text-gray-700">Tomorrow's Plan</label>
                            <textarea name="tomorrow_plan" id="tomorrow_plan" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="What do you plan to work on tomorrow..."></textarea>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/daily-reports" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>
