import "./globals.css";

export const metadata = {
  title: "BCMS",
  description: "ISP Billing & Customer Management System",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body className="bg-slate-50 text-slate-900">
        <div className="min-h-screen flex">
          <aside className="w-64 bg-slate-900 text-white p-4 space-y-2">
            <div className="text-xl font-bold">BCMS</div>
            <nav className="space-y-1 text-sm">
              {[
                "Dashboard","Customers","Subscriptions","Provisionings","Billing","Products","Promotions","Users","Groups","Companies","Brands","Routers","Tickets","Audit Logs","Templates/Reminders"
              ].map((item) => (
                <div key={item} className="rounded px-2 py-1 hover:bg-slate-800 cursor-pointer">{item}</div>
              ))}
            </nav>
          </aside>
          <main className="flex-1 p-6">{children}</main>
        </div>
      </body>
    </html>
  );
}
