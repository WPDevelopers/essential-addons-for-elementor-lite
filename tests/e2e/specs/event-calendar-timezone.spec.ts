import { test, expect } from "@playwright/test";

test.describe("Event Calendar Widget", () => {
	test("keeps manual event end times in the site timezone", async ({ page }) => {
		await page.goto("/event-calendar-timezone-test/");

		const calendar = page.locator(".eael-event-calendar-cls");
		await expect(calendar).toBeVisible();

		const eventTime = page.locator(".fc-list-event-time").first();
		await expect(eventTime).toHaveText("11:00 - 14:00");
		await expect(eventTime).not.toContainText("16:00");
	});
});
