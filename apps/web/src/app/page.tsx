export default function DashboardPage() {
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-semibold">Dashboard</h1>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Card title="Total Customers" value="0" />
        <Card title="Active Customers" value="0" />
        <Card title="Suspended" value="0" />
      </div>
      <div className="rounded border bg-white p-4">
        <h2 className="font-semibold mb-2">Recent Tickets</h2>
        <p className="text-sm text-slate-500">No data yet.</p>
      </div>
    </div>
  );
}

function Card({ title, value }: { title: string; value: string }) {
  return (
    <div className="rounded border bg-white p-4">
      <div className="text-sm text-slate-500">{title}</div>
      <div className="text-2xl font-semibold">{value}</div>
    </div>
  );
}
