<div class="col-12">
    <div class="card">
        <div class="card-body">
            <table>
                <tr>
                    <td><strong>PHP-Version:</strong></td>
                    <td> {{ phpversion() }}</td>
                </tr>
                {{-- <tr>
                    <td><strong>SERVER_ADDR:</strong></td><td> {{ $_SERVER['SERVER_ADDR'] }}</td>
                </tr> --}}
                <tr>
                    <td><strong>HTTP_HOST:</strong> </td>
                    <td>{{ $_SERVER['HTTP_HOST'] }}</td>
                </tr>
                <tr>
                    <td><strong>SERVER_SOFTWARE:</strong></td>
                    <td> {{ $_SERVER['SERVER_SOFTWARE'] }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
